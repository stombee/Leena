<?php

namespace Erenkucukersoftware\BrugsMigrationTool\Facades\Shopify;


use Erenkucukersoftware\BrugsMigrationTool\Facades\Shopify\Components\{Shopify};

use Erenkucukersoftware\BrugsMigrationTool\Facades\GraphQL\QueryBuilder;

class ShopifyFlowFacade {

  public  $shopify;
  public  $queryBuilder;

  public function __construct() {
    $this->shopify = new Shopify();
    $this->queryBuilder = new QueryBuilder();

  }

  public function run ($products){
    $file = $this->shopify->createJsonLineFile($products);
    
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
    $query_builder = $this->queryBuilder;
    $query = $query_builder->generateGetProductQuery($time);
    $shopify = $this->shopify;
    $response = $shopify->run($query);
    return $response;
  }

  
}