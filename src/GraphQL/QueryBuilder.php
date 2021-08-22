<?php

namespace Erenkucukersoftware\BrugsMigrationTool\GraphQL;


use Erenkucukersoftware\BrugsMigrationTool\Constants;

class Quoted {
  private $value;
  public function __construct($value)
  {
      $this->value = $value;
  }
  public function __toString()
  {
      return '"' . $this->value.'"';  
  }
}

class QueryBuilder{

  public $query;
  public $filename;
  public static $operation;
  public $mutation;
  public $queryType;
  public $response;
  public $field;
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


  public static function operation($operation="query"){
    self::$operation = $operation ;
    return new self;
  }

  public function mutation($mutation){
    $this->mutation = $mutation;
    return $this;
  }

  public function fields(Array $fields, $name=""){

    $query = '';
    foreach($fields as $field){
        
        $query .= $field . ' ' ;
    }

    if ($name != "") {
      $this->field = $name .' { '. $query . '}';
    }
    else {
      $this->field = $query;
    }
    
    
    return $this;
  }


  public function queryType($queryType, $input=null, $name=""){
    $this->queryType = $queryType;
    if ($input != null) {
      $input_params = "";
    
      foreach ($input as $key => $value) {
        $input_params .=  $key . ':' . $value . ', ';
      }
      $input_params = substr($input_params, 0, strlen($input_params)-2);
    
      $this->queryType .= " " . $name . "(" . $input_params . ")";

    }
    return $this;
  }

  public function query($input=null, $name, $input_name=""){
    if ($input != null) {
      $input_params = '';
      foreach ($input as $key => $value) {
        $input_params .=  $key . ':' . $value . ', ';
      }
      $input_params = substr($input_params, 0, strlen($input_params)-2);
  
      if($input_name!=""){
        $this->query .= $name . "(" .  "input:{". $input_params . "})";
      }
      else{
        $this->query .= $name . "(" .  $input_params . ")";
      }
     

    }
    return $this;
  }
 

  public function build(){

    $query = '';
    
    if ($this->queryType) {
      $query .= $this->queryType == 'query' ? 'query { ': $this->queryType . '{ ' ;
    }
    
    
    if ($this->query) {
      $query .=  $this->query .'{ ' . $this->field . '}';
    }else {
      $query .= $this->field;
    }
    if ($this->queryType) {
        $query .= ' }';
    }
    return $query;
  
  }



  
}