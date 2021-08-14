<?php

namespace Erenkucukersoftware\BrugsMigrationTool\GraphQL;


use Erenkucukersoftware\BrugsMigrationTool\Constants;


class QueryBuilder{

  public $query;
  public $filename;
  public static $operation;
  public $mutation;
  public $queryType;
  public $response;
  public $fields;
  public $attributes;
  public $cursor = false;
  

  const OPERATION_BULK = 'bulk';
  const OPERATION_CREATE = 'single';

  const QUERYTYPE_MUTATION = 'mutation';
  const QUERYTYPE_QUERY = 'query';
  

  const MUTATION_UPDATE = 'productUpdate';
  const MUTATION_CREATE = 'productCreate';

  const MAIN_FIELDS = 'product';
  const SUB_FIELDS = '';


  public function __construct(){


  }


  public static function operation($operation){
    self::$operation = $operation ;
    return new self;
  }

  public function mutation($mutation){
    $this->mutation = $mutation;
    return $this;
  }

  public function fields(Array $fields){
    $fields = collect($fields);
    $fields = $fields->mapToGroups(function ($item, $key) {
      $item = explode('.',$item);
      return [$item[0]=>$item[1]];
    })->toArray();
    $this->fields = $fields;
    
    return $this;
  }

  public function setFieldAttribute($field,$attributeName,$attributeValue){
    $this->attributes[$field][] = [$attributeName=>$attributeValue];
    return $this;
  }

  public function queryType($queryType){
    $this->queryType = $queryType;
    return $this;
  }

  public function renderFields(){
    
    $fields = '';
    foreach($this->fields as $name => $field){

      $attributes = $this->renderAttributes($name);
      //$fields .= $name. ;
    }
    return $fields;
  }

  public function renderSubFields(){

  }

  public function renderAttributes($field){
    $attributes_arr = $this->attributes[$field];
    
    $attributes = '';
    foreach($attributes_arr as $name => $value){
      dd($name,$value);
      $attributes .= $name.':'.$value;
    }
    dd($attributes);
    return $attributes;
  }



  

  public function renderQuery(){
    $query = '';
    $query .= $this->queryType == 'mutation' ? 'mutation {':'{' ;
    $query .= 
    

    $query .= '}';
    dd($query);
  }



  public function build(){

    $query = $this->renderQuery();

    return $this->query;
  }



  
}