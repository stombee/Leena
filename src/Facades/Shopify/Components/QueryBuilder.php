<?php

namespace Erenkucukersoftware\BrugsMigrationTool\Facades\GraphQL;

use GuzzleHttp\Client;
use Erenkucukersoftware\BrugsMigrationTool\Constants;


class QueryBuilder{

  public $query;
  public $updateable_fields;
  
  

  public function __construct(){
    $this->updateable_fields = Constants::$UPDATEABLE_PRODUCT_FIELDS;
    

  }



  public function generateGraphQLQuery($stagedUploadPath,$fields){
    

    $fields_for_query = '';
    if (in_array('all', $fields)) {
      foreach($this->updateable_fields as $updateable){
        $fields_for_query .= $updateable . ' ';
      }
    }
    
    foreach($fields as $field){
      $fields_for_query .= $field.' ';
    }
    
    $query = 'mutation {
  bulkOperationRunMutation(
    mutation: "mutation call($input: ProductInput!) { productUpdate(input: $input) { product {'.$fields_for_query.'} userErrors { message field } } }",
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

  $this->query = $query;
  return $this;

  }

  public function generateStageUploadQuery($filename){
    $filename = explode('.', $filename);
    $filename = $filename[0];

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

    $this->query = $query;
    return $this;
  }

  public function generateBulkOperationStatusQuery(){

  }

  public function generateBulkOperationStatusCheckQuery($bulkOperationID){
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
  $this->query = $query;
  return $this;
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
    $this->query = $query;
    
    return $this->query;
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


  
}