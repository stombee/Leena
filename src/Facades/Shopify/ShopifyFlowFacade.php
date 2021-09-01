<?php

namespace Erenkucukersoftware\BrugsMigrationTool\Facades\Shopify;


use Erenkucukersoftware\BrugsMigrationTool\Facades\Shopify\Components\{Shopify};
use Carbon\Carbon;
use Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder;
use Erenkucukersoftware\BrugsMigrationTool\BrugsMigrationTool;
use Illuminate\Support\Facades\Log;
use Storage;

class ShopifyFlowFacade {

  public  $shopify;
  public  $queryBuilder;

  public function __construct() {
    $this->shopify = new Shopify();
    $this->queryBuilder = new QueryBuilder();

  }
  public function __destruct() {
    Storage::delete('/process/'.Shopify::$default_storage_path.'/*.jsonl');
  }



  public function run ($products){

    if(count($products) < 1){
      return 'Product count less than 1';
    }
    
    $files = $this->shopify->createJsonLineFile($products);
    
    $results = [];
    foreach( $files as $file ){
      $staged_upload_path = $this->shopify->uploadShopifyStagedFiles($file);
        Log::debug(['file',$file]);
        Log::debug(['staged_path',$staged_upload_path]);
      if(BrugsMigrationTool::$settings['OPERATION'] == 'UPDATE')
      {
        $bulk_operation_id = $this->shopify->updateProducts($staged_upload_path);
        Log::debug(['bulk_op',$bulk_operation_id]);
        $file_content = $this->shopify->getBulkActionResult($bulk_operation_id);
        Log::debug(['file content',$file_content]);
        $results[] = $file_content;
        
      }

      if (BrugsMigrationTool::$settings['OPERATION'] == 'CREATE') {
        $bulk_operation_id = $this->shopify->createProducts($staged_upload_path);
        Log::debug(['bulk_op', $bulk_operation_id]);
        $file_content = $this->shopify->getBulkActionResult($bulk_operation_id);
        Log::debug(['file content', $file_content]);
        $results[] = $file_content;
        
      }

      
    }

    

    
    return [$results];
    //Storage::disk('local')->put('/process/process_results/shopify_result.jsonl', $results);
    

    
  }
  
  public function getStagedUploadPath($file){

  }
  
  
  /*
  public  function getAllProducts()
  {
    $query_builder = $this->queryBuilder;

    $response = 1;
    $count = 1;
    $cursor = false;
    while($count != 82){
      $query = $query_builder->generateGetProductsQuery($cursor);
      $shopify = $this->shopify;
      $response = $shopify->run($query);
      
      $cursor = isset($response->data) ? $response->data->products->edges[249]->cursor:false;
      
      if($count > 76){
        dump($response,$query);
      }
      $count++;
      sleep(2);
    }
    dd('f');
    return $response;
  }
*/

  public function getProducts($time){
    //$query_builder = $this->queryBuilder;
    //$query = $query_builder->generateGetProductQuery($time);
    //$shopify = $this->shopify;
    //$response = $shopify->run($query);
    //return $response;
  }

  
}