<?php

namespace Erenkucukersoftware\BrugsMigrationTool;



use Illuminate\Support\Facades\Log;
use Erenkucukersoftware\BrugsMigrationTool\Constants;


use Erenkucukersoftware\BrugsMigrationTool\Facades\Product\ProductFacade;


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
        'CUSTOM_GROUP' => null
    ];
    
    public function __construct($entity, $fields, $entity_type,$operation,$isDev = false,$custom_group = null) 
    {
        self::$settings = [
            'ENTITY' => explode(',', $entity),
            'FIELDS' => explode(',', $fields),
            'ENTITY_TYPE' => $entity_type,
            'OPERATION' => $operation,
            'IS_DEV' => $isDev,
            'UPDATEABLE_FIELDS' => Constants::$UPDATEABLE_PRODUCT_FIELDS,
            'API_MODE' =>  'GRAPHQL',
            'CUSTOM_GROUP' => $custom_group
        ];

    }

    public function createProducts(){
        
        $products_facade = new ProductFacade();
        
        
        $shopify = new ShopifyFlowFacade();
        $response = $shopify->run($product_fields);
        return $response;
    }

    public function updateProducts()
    {
        $product_fields = new ProductFields($this->isDev);
        $product_fields = $product_fields->getFieldsForAll($this->settings['FIELDS']);
        
        
        $product_jsonl_file = $this->createJsonLineFile($product_fields);
        
        //$staged_upload_path = $this->uploadToShopify($product_jsonl_file);
        //Log::debug('shopify json uploaded ');
        //$graph_response = new GraphQL;
        //$bulk_operation_status = $graph_response->generateGraphQLQuery($staged_upload_path,$this->fields)->send();
        
        return 
        'You follow your bulk action with this id '/*.$bulk_operation_status->data->bulkOperationRunMutation->bulkOperation->id*/;
    }



    



    public function run()
    {
        


        //PRODUCT UPDATES
        if (self::$settings['ENTITY_TYPE'] == 'Products' && self::$settings['OPERATION'] == 'update') {
            $this->response = $this->updateProducts();
        }

        

        if(self::$settings['OPERATION'] == 'create') {
            $this->response = $this->createProducts();
        }

        return $this;
    }

    public function getResponse()
    {
        return $this->response;
    }


}
