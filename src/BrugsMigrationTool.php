<?php

namespace Erenkucukersoftware\BrugsMigrationTool;



use Illuminate\Support\Facades\Log;
use Erenkucukersoftware\BrugsMigrationTool\Constants;

use App\Events\ProductsCreated;
use App\Events\ProductsUpdated;
use Carbon\Carbon;

use Erenkucukersoftware\BrugsMigrationTool\Facades\Product\ProductFacade;
use Erenkucukersoftware\BrugsMigrationTool\Facades\Shopify\ShopifyFlowFacade;
use Erenkucukersoftware\BrugsMigrationTool\Facades\Shopify\Components\Query;

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
    
    public function __construct($entity, $fields, $entity_type,$operation,$custom_group = null) 
    {
        
        $entity_array = explode(',', $entity);
        self::$settings = [
            'ENTITY' => $entity_array,
            'FIELDS' => explode(',', $fields),
            'ENTITY_TYPE' => $entity_type,
            'OPERATION' => $operation,
            'IS_DEV' => false,
            'UPDATEABLE_FIELDS' => Constants::$UPDATEABLE_PRODUCT_FIELDS,
            'API_MODE' =>  'GRAPHQL',
            'CUSTOM_GROUP' => $custom_group,
            'MODE' => $entity_array[0] == 'all' ? 'ALL':'PARTIAL'
        ];

    }

    public function createProducts(){
        
        $products = (new ProductFacade())
        ->run();
        
        $shopify_response = (new ShopifyFlowFacade())
        ->run($products);
       
        
        
        //ProductsCreated::dispatch();
        return $shopify_response;
    }

    public function updateProducts()
    {
        
        $products = (new ProductFacade())
        ->run();
        
        $shopify_response = (new ShopifyFlowFacade())
        ->run($products);
        
        
        //ProductsUpdated::dispatch($time);
        //return $shopify_response;

    }



    




    public function getResponse(){
        return $this->response;
    }

    public function run(){



        //PRODUCT UPDATES
        if (self::$settings['ENTITY_TYPE'] == 'Product' && self::$settings['OPERATION'] == 'UPDATE') {
            $this->response = $this->updateProducts();
        }



        if (self::$settings['OPERATION'] == 'CREATE') {
            $this->response = $this->createProducts();
        }

        return $this;
    }



}
