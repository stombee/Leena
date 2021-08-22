<?php

namespace Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder\Operation;

use Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder\Utils\Brackets;
use Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder\Utils\Quoted;

class Operation
{
    public $nodes;
    public $queryType;
    public function render()
    {
        $query = "";
        $query .= $this->queryType . Brackets::curlies($this->nodes->render());

        return $query;
    }
    public function addFields(array $fields)
    {
        $this->nodes->addFields($fields);
    }
    public function addSubFields($fieldName, array $fields)
    {
        $this->nodes->addSubFields($fieldName, $fields);
    }
    public function addAttribute($field, $attributeName, $attributeValue)
    {
        $this->nodes->addAttribute($field, $attributeName, $attributeValue);
    }
};
