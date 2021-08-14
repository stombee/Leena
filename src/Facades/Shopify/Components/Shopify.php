<?php

namespace Erenkucukersoftware\BrugsMigrationTool\Facades\Shopify\Components;

use Rs\JsonLines\JsonLines;
use Storage;


class Shopify{

  public $response_data;

 
  public function uploadToShopify($filename)
  {
    $client = new \GuzzleHttp\Client();
    $graphql = new GraphQL;
    $stage_upload_meta = $graphql->generateStageUploadQuery($filename)->send();
    $stage_upload_meta = $stage_upload_meta->data->stagedUploadsCreate->stagedTargets[0]->parameters;
    $file_content = Storage::disk('public')->get($filename);
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

    return $response_body_xml->Key;
  }


  public function createJsonLineFile($products){
    $jsonLines = (new JsonLines())->enline($products);
    $file_name = 'product_create_feed.jsonl';
    $jsonl_file = Storage::disk('public')->put($file_name, $jsonLines);

    if ($jsonl_file) {
      return $file_name;
    }
  }


  public static function checkBulkActionStatus($bulkActionID)
  {
    $graphql = new GraphQL;
    $response = $graphql->generateBulkOperationStatusCheckQuery($bulkActionID)->send();

    return $response;
  }


  public function run($query)
  {
    $client = new \GuzzleHttp\Client();



    $response = $client->request('POST', 'https://boutiquerugs-dev.myshopify.com/admin/api/2021-07/graphql.json', [
      'headers' => [
        'Content-Type' => 'application/json',
        'X-Shopify-Access-Token' => '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'
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

  


}