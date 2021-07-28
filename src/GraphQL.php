<?php

namespace Erenkucukersoftware\BrugsMigrationTool;

use GuzzleHttp\Client;
use Erenkucukersoftware\BrugsMigrationTool\Constants;


class GraphQL{

  public $query = null;
  public $updateable_fields = null;

  public function __construct(){
    $this->updateable_fields = Constants::$UPDATEABLE_PRODUCT_FIELDS;
  }

  public function send(){
    $client = new \GuzzleHttp\Client();
    $query = $this->query;


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

    return $data;
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


 
  

}