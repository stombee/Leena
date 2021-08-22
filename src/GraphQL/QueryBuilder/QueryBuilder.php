<?php

namespace Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder;

use Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder\Nodes;
use Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder\Operation\BulkOperation;
use Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder\Operation\SingleOperation;


const stagedUploadsCreateText = <<<EOT
            mutation {
                stagedUploadsCreate(input:{
                resource: BULK_MUTATION_VARIABLES,
                filename: "sometry",
                mimeType: "text/jsonl",
                httpMethod: POST
                })
            EOT;

class ProductUpdateNodes extends Nodes
{
}

class QueryBuilder
{

  public $query;
  public $filename;
  public static $operation;
  public $mutation;
  public $queryType;
  public $response;
  public $field;
  public $attributes;
  public $cursor = false;
  public $nodes;


  const OPERATION_BULK = 'bulk';
  const OPERATION_CREATE = 'single';

  const QUERYTYPE_MUTATION = 'mutation';
  const QUERYTYPE_QUERY = 'query';


  const MUTATION_UPDATE = 'productUpdate';
  const MUTATION_CREATE = 'productCreate';
  const STAGED_UPLOADS_CREATE = 'stagedUploadsCreate';

  const MAIN_FIELDS = 'product';
  const SUB_FIELDS = '';


  public function __construct()
  {
    self::$operation->nodes = new Nodes();
  }


  public static function operation($operation = "query")
  {
    if ($operation == self::OPERATION_BULK) {
      self::$operation = new BulkOperation();
    } else {
      self::$operation = new SingleOperation();
    }

    return new self;
  }

  public function mutation($mutation)
  {
    if ($mutation == self::STAGED_UPLOADS_CREATE) {
      $this->mutation = stagedUploadsCreateText;
    } else if ($mutation == self::MUTATION_UPDATE) {
      self::$operation->nodes = new ProductUpdateNodes();
    } else {
      self::$operation->nodes = new Nodes();
    }

    return $this;
  }

  public function queryType($queryType)
  {
    self::$operation->queryType = $queryType;
    return $this;
  }


  public function fields(array $fields)
  {
    self::$operation->addFields($fields);
    return $this;
  }
  public function setFieldAttribute($field, $attributeName, $attributeValue)
  {
    self::$operation->addAttribute($field, $attributeName, $attributeValue);
    return $this;
  }

  public function renderQuery()
  {
    return self::$operation->render();
  }

  public function build()
  {
    return $this->renderQuery();
  }
}
