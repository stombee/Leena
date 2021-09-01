<?php

namespace Erenkucukersoftware\BrugsMigrationTool\Facades\Shopify\Components;

use GuzzleHttp\Client;
use Erenkucukersoftware\BrugsMigrationTool\Constants;


class QueryBuilder{

  public $query;
  public $updateable_fields;
  public $prefer;
  

  public function __construct(){
    $this->updateable_fields = Constants::$UPDATEABLE_PRODUCT_FIELDS;
    

  }
  
  public function prefer($prefer)
  {
    $this->query['prefer'] = $prefer;
    return $this;
  }


  public function productUpdateQuery($stagedUploadPath){
    $query = 'mutation {
  bulkOperationRunMutation(
    mutation: "mutation call($input: ProductInput!) { productUpdate(input: $input) { product { id } userErrors { message field } } }",
    stagedUploadPath: "'.$stagedUploadPath.'") {
    bulkOperation {
    
      id
      url
      status
    }
    userErrors {
      message
      field
    }
  }
}';

  
  return $query;

  }

  public function productCreateQuery($stagedUploadPath)
  {
    $query = 'mutation {
  bulkOperationRunMutation(
    mutation: "mutation call($input: ProductInput!) { productCreate(input: $input) { product { id } userErrors { message field } } }",
    stagedUploadPath: "' . $stagedUploadPath . '") {
    bulkOperation {
    
      id
      url
      status
    }
    userErrors {
      message
      field
    }
  }
}';


    return $query;


  }

  public function generateStageUploadQuery($filename){


    $query = 'mutation {
      stagedUploadsCreate(input:{
        resource: BULK_MUTATION_VARIABLES,
        filename: "'.$filename.'",
        mimeType: "text/jsonl",
        httpMethod: POST
      }){
        userErrors{
          field,
          message
        },
        stagedTargets{
          url,
          resourceUrl,
          parameters {
            name,
            value
          }
        }
      }
    }';

    
    return $query;
  }

  public function generateBulkOperationStatusQuery(){

  }

  public function bulkOperationStatusCheckQuery($bulkOperationID){
    $query = '{
  node(id: "'.$bulkOperationID.'") {
    ... on BulkOperation {
      id
      status
      errorCode
      createdAt
      completedAt
      objectCount
      fileSize
      url
      partialDataUrl
    }
  }
}';
   
  return $query;
  }

  public function generateGetProductsQuery($cursor){


      $query = '
      {
  products(first: 250'.($cursor ? ',after:'.$cursor:null).') {
    edges {
      cursor
      node {
        id
        title
        
      }
    }
  }
}
      ';
 
    
    return $query;
  }

  public function generateGetProductQuery($time){
    $query_field = 'created_at:>'.$time;

    $query = '
    {
  products(first: 250, query:"'.$query_field. ' ") {
    edges {
      cursor
      node {
        id
        title
        vendor
        status
        tags
        images(first: 20) {
        edges {
          node {
            src
            altText
          }
        }
      }
         variants(first: 20) {
      edges {
        node {
          id
          inventoryQuantity
        }
      }
    }
      }
    }
  }
}
    
    ';
    $this->query = $query;
    return $this->query;
    

  }
  public function generateLatestProcessedProducts($timestamp,$after)
  {
      $after = $after ? '&since_id='.$after:'';
      $query = 'products.json?created_at_min='.$timestamp.'&limit=250'.$after;
      return $query;
  }

  public function generateGetMetafields($id)
  {
    $query = 'products/'.$id.'/metafields.json';
    return $query;
  }



  
}