<?php

namespace Erenkucukersoftware\BrugsMigrationTool\GraphQL;


use Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder\QueryBuilder;
use Erenkucukersoftware\BrugsMigrationTool\BrugsMigrationTool;

class GraphQL
{
  public $query_url;
  public $token;

  public function __construct()
  {
    $this->query_url = !BrugsMigrationTool::$settings['IS_DEV'] ? secEnv('SHOPIFY_PRODUCTION_URL') : secEnv('SHOPIFY_DEV_URL');
    $this->token = BrugsMigrationTool::$settings['IS_DEV'] ? secEnv('SHOPIFY_DEV_TOKEN') : secEnv('SHOPIFY_PRODUCTION_TOKEN');
  }

  public function productUpdate($staged_upload_path)
  {

    $query =
      QueryBuilder::operation('bulk')
      ->queryType('mutation')
      ->mutation('productUpdate')
      ->fields(['products.id', 'products.title', 'variants.title', 'images.src'])
      ->setFieldAttribute('products', 'first', '250')
      ->setFieldAttribute('products', 'query', 'blabla')
      ->build();
      dd($query);
    return $query;
  }

  public function stagedUploadCreate()
  {
    $query =
      QueryBuilder::operation('bulk')
      ->queryType('mutation')
      ->mutation('stagedUploadsCreate')
      ->fields(['id', 'url'])
      ->build();

    return $query;
  }

  public function getProducts()
  {
    $query =
      QueryBuilder::operation('single')
      ->queryType('query')
      ->fields(['products.id', 'products,title'])
      ->setFieldAttribute('products', 'first', '25')
      ->build();

    return $query;
  }


  public function run($query, $prefer = 'graphql')
  {
    $client = new \GuzzleHttp\Client();
    if ($prefer == 'rest') {
      $response = $client->request('GET', $this->query_url . '/admin/api/2021-07/' . $query, [
        'headers' => [
          'Content-Type' => 'application/json',
          'X-Shopify-Access-Token' => $this->token
        ]

      ]);

      $json = $response->getBody()->getContents();
      $body = json_decode($json);
      $data = $body;

      $this->response_data = $data;

      return $data;
    }




    $response = $client->request('POST', $this->query_url . '/admin/api/2021-07/graphql.json', [
      'headers' => [
        'Content-Type' => 'application/json',
        'X-Shopify-Access-Token' => $this->token
      ],
      'json' => [
        'query' => $query
      ]
    ]);

    $json = $response->getBody()->getContents();
    $body = json_decode($json);
    $data = $body;

    $this->response_data = $data;

    return $data;
  }
}
