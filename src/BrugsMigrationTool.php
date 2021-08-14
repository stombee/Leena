<?php

namespace Erenkucukersoftware\BrugsMigrationTool;



use Illuminate\Support\Facades\Log;
use Erenkucukersoftware\BrugsMigrationTool\Constants;

use App\Events\ProductsCreated;


use Erenkucukersoftware\BrugsMigrationTool\Facades\Product\ProductFacade;
use Erenkucukersoftware\BrugsMigrationTool\Facades\Shopify\ShopifyFlowFacade;
use Carbon\Carbon;
use Erenkucukersoftware\BrugsMigrationTool\Enums\Operation;
use Erenkucukersoftware\BrugsMigrationTool\GraphQL\GraphQL;



class BrugsMigrationTool
{
    public $response;
    public static $settings = [
        'ENTITY' => null,
        'FIELDS' => null,
        'ENTITY_TYPE' => null,
        'OPERATION' => null,
        'IS_DEV' => false,
        'UPDATEABLE_FIELDS' => null,
        'API_MODE' => 'GRAPHQL',
        'CUSTOM_GROUP' => null,
        'MODE' => null
    ];
    
    public function __construct($entity, $fields, $entity_type,$operation,$isDev = false,$custom_group = null) 
    {
        
        $entity_array = explode(',', $entity);

        self::$settings = [
            'ENTITY' => $entity_array,
            'FIELDS' => explode(',', $fields),
            'ENTITY_TYPE' => $entity_type,
            'OPERATION' => $operation,
            'IS_DEV' => $isDev,
            'UPDATEABLE_FIELDS' => Constants::$UPDATEABLE_PRODUCT_FIELDS,
            'API_MODE' =>  'GRAPHQL',
            'CUSTOM_GROUP' => $custom_group,
            'MODE' => $entity_array[0] == 'all' ? 'ALL':'PARTIAL'
        ];

    }

    public function createProducts(){
        $query = (new GraphQL)
        ->productUpdate();
        $products = (new ProductFacade())
        ->run();
        $shopify_response = (new ShopifyFlowFacade())
        ->run($products);
        $time = Carbon::now()->toIso8601String();
        ProductsCreated::dispatch($products,$time);
        return $shopify_response;
    }

    public function updateProducts()
    {/*
        $product_fields = new ProductFields($this->isDev);
        $product_fields = $product_fields->getFieldsForAll($this->settings['FIELDS']);
        
        
        $product_jsonl_file = $this->createJsonLineFile($product_fields);
        
        $staged_upload_path = $this->uploadToShopify($product_jsonl_file);
        Log::debug('shopify json uploaded ');
        $graph_response = new GraphQL;
        $bulk_operation_status = $graph_response->generateGraphQLQuery($staged_upload_path,$this->fields)->send();
        
        return 
        'You follow your bulk action with this id '.$bulk_operation_status->data->bulkOperationRunMutation->bulkOperation->id

        */
    }



    




    public function getResponse(){
        return $this->response;
    }

    public function run(){



        //PRODUCT UPDATES
        if (self::$settings['ENTITY_TYPE'] == 'Products' && self::$settings['OPERATION'] == 'UPDATE') {
            $this->response = $this->updateProducts();
        }



        if (self::$settings['OPERATION'] == 'CREATE') {
            $this->response = $this->createProducts();
        }

        return $this;
    }



}
