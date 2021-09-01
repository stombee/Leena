<?php

namespace Erenkucukersoftware\BrugsMigrationTool\GraphQL;


use Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder;

class GraphQL{

  public function productUpdate(){
    
    $query = 
      QueryBuilder::operation('bulk')
      ->queryType('mutation')
      ->mutation('productUpdate')
      ->fields(['products.id','products.title', 'variants.title', 'images.src'])
      ->setFieldAttribute('products','first','250')
      ->setFieldAttribute('products','query','blabla')
      ->setFieldAttribute('bulkOperationRunMutation', 'stagedUploadPath','filepath')
      ->build();
      return $query;
  }

  public function stagedUploadCreate(){
    $query =
      QueryBuilder::operation('single')
      ->queryType('mutation')
      ->mutation('stagedUploadsCreate')
      ->fields(['id','url'])
      ->build();

    return $query;
  }

  public function getProducts(){
    $query =
      QueryBuilder::operation('single')
      ->queryType('query')
      ->fields(['products.id','products,title'])
      ->setFieldAttribute('products', 'first', '25')
      ->build();

    return $query;

  }





}