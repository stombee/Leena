<?php

namespace Erenkucukersoftware\BrugsMigrationTool\Facades\Shopify;

use Erenkucukersoftware\BrugsMigrationTool\Enums\{Fields, Operations, ToolStatus, CustomGroups, ApiMode};
use Erenkucukersoftware\BrugsMigrationTool\Events\{ProductsCreated, ProductsUpdated};
use Erenkucukersoftware\BrugsMigrationTool\Facades\Shopify\Components\{Shopify};
use Erenkucukersoftware\BrugsMigrationTool\BrugsMigrationTool;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Storage;


class ShopifyFlowFacade 
{
  public  $shopify;
  public $graphQL;
  public function __construct() 
  {
    $this->shopify = new Shopify();
    //$this->graphQL = new GraphQL();

  }
  public function __destruct() 
  {
    Storage::delete('/process/'.Shopify::$default_storage_path.'/*.jsonl');
  }



  public function run ($products)
  {
    
    if($products && count($products) < 1)
    {
      return 'Product count less than 1';
    }
    
    $files = $this->shopify->createJsonLineFile($products);
    $results = [];
    dd('shopify prevented');
    foreach( $files as $file )
    {
      $staged_upload_path = $this->shopify->uploadShopifyStagedFiles($file);
        Log::debug(['file',$file]);
        Log::debug(['staged_path',$staged_upload_path]);
      if(BrugsMigrationTool::$settings['OPERATION']->is(Operations::Update))
      {
        $bulk_operation_id = $this->shopify->updateProducts($staged_upload_path);
        Log::channel('update_logs')->debug(['bulk_op',$bulk_operation_id]);
        
        
        $file_content = $this->shopify->getBulkActionResult($bulk_operation_id);
        Log::channel('update_logs')->debug(['file content',$file_content]);
        $results[] = $file_content;
         
      }

      if (BrugsMigrationTool::$settings['OPERATION']->is(Operations::Create)) 
      {
        $bulk_operation_id = $this->shopify->createProducts($staged_upload_path);
        Log::channel('create_logs')->debug(['bulk_op', $bulk_operation_id]);

        
        

        $file_content = $this->shopify->getBulkActionResult($bulk_operation_id);
        Log::channel('create_logs')->debug(['file content', $file_content]);
        $results[] = $file_content;
        
      }


      
    }
    /*
    if(BrugsMigrationTool::$settings['OPERATION'] == 'UPDATE')
    {
      ProductsUpdated::dispatch($timestamp);  //dispatch event
    }
    if (BrugsMigrationTool::$settings['OPERATION'] == 'CREATE') {
      ProductsCreated::dispatch($timestamp);
    }
    */
    

    
    return [$results];
    //Storage::disk('local')->put('/process/process_results/shopify_result.jsonl', $results);
    

    
  }
  
}