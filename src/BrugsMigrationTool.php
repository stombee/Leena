<?php

namespace Erenkucukersoftware\BrugsMigrationTool;

use Illuminate\Support\Facades\DB;
use App\Models\ShopifyProduct;
use GuzzleHttp\Client;
use Storage;
use Rs\JsonLines\JsonLines;
use Illuminate\Support\Facades\Log;

use Erenkucukersoftware\BrugsMigrationTool\ProductFields;
use Erenkucukersoftware\BrugsMigrationTool\GraphQL;
use Erenkucukersoftware\BrugsMigrationTool\Constants;

class BrugsMigrationTool
{
    public $fields = '';
    public $entities_to_update = '';
    public $entity_type = '';
    public $isDev = false;
    public $updateable_fields = null;
    public $entity_mode = 'all';
    public $response = null;
    
    public function __construct($entity, $fields, $entity_type)
    {
        $this->updateable_fields = Constants::$UPDATEABLE_PRODUCT_FIELDS;
        


        if(strpos($fields,'all')){
            $this->entity_mode = 'all';
        }else{
            $this->entity_mode = 'singleorfew';
        }


        $this->fields = explode(',', $fields);


        $this->entities_to_update = explode(',', $entity);
        $this->entity_type = $entity_type;
        
    }



    public function updateAllProducts()
    {
        $product_fields = new ProductFields($this->isDev);
        $product_fields = $product_fields->getFieldsForAll($this->fields);
        Log::debug('all fields completed');
        
        $product_jsonl_file = $this->createJsonLineFile($product_fields);
        Log::debug('jsonline completed');
        //$staged_upload_path = $this->uploadToShopify($product_jsonl_file);
        //Log::debug('shopify json uploaded ');
        //$graph_response = new GraphQL;
        //$bulk_operation_status = $graph_response->generateGraphQLQuery($staged_upload_path,$this->fields)->send();
        
        return 
        'You follow your bulk action with this id '/*.$bulk_operation_status->data->bulkOperationRunMutation->bulkOperation->id*/;
    }

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
            ['name' => 'file','contents' => $file_content ,'filename' => $filename]
        ];
        
        //UPLOAD FIELDS
        $response = $client->request('POST', 'https://shopify.s3.amazonaws.com', [

            'multipart' => $stage_upload_meta
        ]);
        $response_body = $response->getBody()->getContents();
        $response_body_xml = simplexml_load_string($response_body);
       
        return $response_body_xml->Key;
    }

    public function createJsonLineFile($products)
    {



        
     

        $jsonLines = (new JsonLines())->enline($products);
        

        $file_name = 'product_update_feed.jsonl';
        $jsonl_file = Storage::disk('public')->put($file_name, $jsonLines);
        
        if ($jsonl_file) {
            return $file_name;
        }
        

        
    }

    public function updateSingleOrFew()
    {
    }

    public function run($run_mode = 'prod')
    {
        if ($run_mode == 'dev') {
            $this->isDev = true;
        }
        //PRODUCT UPDATES
        if ((count($this->entities_to_update) > 500 || in_array('all', $this->entities_to_update)) && $this->entity_type == 'Products') {
            $this->response = $this->updateAllProducts();
        }

        return $this;
    }

    public function response()
    {
        return $this->response;
    }

    public static function checkBulkActionStatus($bulkActionID)
    {
        $graphql = new GraphQL;
        $response = $graphql->generateBulkOperationStatusCheckQuery($bulkActionID)->send();

        return $response;
    }
}
