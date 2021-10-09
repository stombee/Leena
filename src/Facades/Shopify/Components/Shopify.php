<?php

namespace Erenkucukersoftware\BrugsMigrationTool\Facades\Shopify\Components;

use Erenkucukersoftware\BrugsMigrationTool\Facades\Shopify\Components\QueryBuilder;
use Erenkucukersoftware\BrugsMigrationTool\Enums\{Fields, Operations, ToolStatus, CustomGroups, ApiMode};
use Erenkucukersoftware\BrugsMigrationTool\BrugsMigrationTool;
use Rs\JsonLines\JsonLines;
use Storage;



class Shopify
{
  public $response_data;
  public $maximum_chain_bulks = 4;
  public $jsonline_partitioned;
  public $maximum_file_size = 19000000;
  public static $default_storage_path = 'tmp_files';
  public $query_url;
  public $token;

  public function __construct()
  {
    $this->query_url = !BrugsMigrationTool::$settings['IS_DEV'] ? 'https://boutiquerugs.myshopify.com' : 'https://boutiquerugs-dev.myshopify.com';
    $this->token = BrugsMigrationTool::$settings['IS_DEV'] ? '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' : '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';
  }


  public function createProducts($stagedUploadPath)
  {
    $query = (new QueryBuilder)->productCreateQuery($stagedUploadPath);
    $response = $this->run($query);
    return $response->data->bulkOperationRunMutation->bulkOperation->id;
  }

  public function uploadShopifyStagedFiles($filename)
  {
    $client = new \GuzzleHttp\Client();
    $query = (new QueryBuilder)->generateStageUploadQuery($filename);

    $response = $this->run($query);

    $stage_upload_meta = $response->data->stagedUploadsCreate->stagedTargets[0]->parameters;
    $file_content = Storage::disk('local')->get('/process/tmp_files/' . $filename);
    $stage_upload_meta = [
      ['name' => $stage_upload_meta[0]->name, 'contents' => $stage_upload_meta[0]->value],
      ['name' => $stage_upload_meta[1]->name, 'contents' => $stage_upload_meta[1]->value],
      ['name' => $stage_upload_meta[2]->name, 'contents' => $stage_upload_meta[2]->value],
      ['name' => $stage_upload_meta[3]->name, 'contents' => $stage_upload_meta[3]->value],
      ['name' => $stage_upload_meta[4]->name, 'contents' => $stage_upload_meta[4]->value],
      ['name' => $stage_upload_meta[5]->name, 'contents' => $stage_upload_meta[5]->value],
      ['name' => $stage_upload_meta[6]->name, 'contents' => $stage_upload_meta[6]->value],
      ['name' => $stage_upload_meta[7]->name, 'contents' =>  $stage_upload_meta[7]->value],
      ['name' => $stage_upload_meta[8]->name, 'contents' => $stage_upload_meta[8]->value],
      ['name' => 'file', 'contents' => $file_content, 'filename' => $filename]
    ];

    //UPLOAD FIELDS
    $response = $client->request('POST', 'https://shopify.s3.amazonaws.com', [

      'multipart' => $stage_upload_meta
    ]);
    $response_body = $response->getBody()->getContents();
    $response_body_xml = simplexml_load_string($response_body);

    return (string) $response_body_xml->Key;
  }

  public function updateProducts($stagedUploadPath)
  {
    $query = (new QueryBuilder)->productUpdateQuery($stagedUploadPath);

    $response = $this->run($query);

    return $response->data->bulkOperationRunMutation->bulkOperation->id;
  }





  public function createJsonLineFile($products)
  {

    $jsonlines = (new JsonLines())->enline($products);
    $jsonline_size = strlen($jsonlines);

    $file_name = $this->createFileName();


    if ($jsonline_size < $this->maximum_file_size) 
    {
      Storage::disk('local')->put('/process/' . self::$default_storage_path . '/' . $file_name, $jsonlines);
      return [$file_name];
    }

    $part_count = ceil($jsonline_size / $this->maximum_file_size);
    $part_size = $jsonline_size / $part_count;
    $json_line_parts = $this->splitPartJsonLines($jsonlines, $part_size, $part_count);
    $partitioned_files = [];
    foreach ($json_line_parts as $part_name => $part_jsonlines) 
    {
      Storage::disk('local')->put('/process/' . self::$default_storage_path . '/' . $part_name, $part_jsonlines);

      $partitioned_files[] = $part_name;
    }

    return $partitioned_files;
  }
  public function createFileName($part = null)
  {
    $file_name = '';
    $file_name .= strtolower('product');
    $file_name .= '_';
    $file_name .= strtolower(BrugsMigrationTool::$settings['OPERATION']->is(Operations::Create) ? 'create' : 'update');
    $file_name .= '_';
    $part ? $file_name .= 'part' : null;
    $part ? $file_name .= '_' : null;
    $part ? $file_name .= $part : null;
    $part ? $file_name .= '_' : null;
    $file_name .= date('Y-m-d-H-i');
    $file_name .= '.jsonl';
    return $file_name;
  }

  public function getBulkActionResult($bulk_operation_id)
  {
    $status = '';
    $file_url = '';
    while ($status != 'COMPLETED') 
    {
      ['status' => $status, 'file_url' => $file_url] = $this->checkBulkActionStatus($bulk_operation_id);
      sleep(60);
    }

    $response_jsonline = file_get_contents($file_url);
    return $response_jsonline;
  }

  public function checkBulkActionStatus($bulkActionID)
  {
    $query = (new QueryBuilder)->bulkOperationStatusCheckQuery($bulkActionID);
    $response = $this->run($query);
    $response = ['status' => $response->data->node->status, 'file_url' => $response->data->node->url];
    return $response;
  }

  public function getLatestProcessedProducts($timestamp)
  {

    $after = null;
    $query = (new QueryBuilder)->generateLatestProcessedProducts($timestamp, $after);
    $response = $this->run($query, 'rest');
    $products = [];

    while (count($response->products) > 0) 
    {
      $after = end($response->products)->id;
      $query = (new QueryBuilder)->generateLatestProcessedProducts($timestamp, $after);
      $response = $this->run($query, 'rest');
      foreach ($response->products as $product) {
        $query = (new QueryBuilder)->generateGetMetafields($product->id);
        $product->metafields = ($this->run($query, 'rest'))->metafields;

        $products[] = $product;
      }
    }
    return $products;
  }

  public function run($query, $prefer = 'graphql')
  {
    $client = new \GuzzleHttp\Client();
    if ($prefer == 'rest') 
    {
      $response = $client->request('GET', $this->query_url . '/admin/api/2021-07/' . $query, [
        'headers' => [
          'Content-Type' => 'application/json',
          'X-Shopify-Access-Token' => $this->token
        ]

      ]);

      $json = $response->getBody()->getContents();
      $body = json_decode($json);
      $data = $body;

      $this->response_data = $data;

      return $data;
    }




    $response = $client->request('POST', $this->query_url . '/admin/api/2021-07/graphql.json', [
      'headers' => [
        'Content-Type' => 'application/json',
        'X-Shopify-Access-Token' => $this->token
      ],
      'json' => [
        'query' => $query
      ]
    ]);

    $json = $response->getBody()->getContents();
    $body = json_decode($json);
    $data = $body;

    $this->response_data = $data;

    return $data;
  }

  public function splitPartJsonLines($json_lines, $part_size, $part_count)
  {
    $json_lines_arr = explode("\n", $json_lines);
    $json_line_parts = [];
    $last_index = 0;
    for ($i = 1; $i <= $part_count; $i++) 
    {
      $json_lines = '';
      $file_name = $this->createFileName($i);

      foreach ($json_lines_arr as $index => $json_line) 
      {
        if ($index < $last_index) 
        {
          continue;
        }
        if (strlen($json_lines) >= $part_size) 
        {
          $last_index = $index;
          break;
        }

        $json_lines .= $json_line . "\n";
      }
      $json_line_parts[$file_name] = $json_lines;
    }

    return $json_line_parts;
  }
}
