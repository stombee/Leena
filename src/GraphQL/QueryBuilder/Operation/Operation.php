<?php

namespace Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder\Operation;

use Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder\Utils\Brackets;
use Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder\Utils\Quoted;
use Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder\Nodes;

class Operation
{
    public $nodes;
    public $queryType;
    public $mutation;
    public $isCursored;

    public function __construct()
    {
        $this->nodes = new Nodes();
        $this->nodes->isCursored = false;
    }

    public function render()
    {
        $query = "";
        $query .= $this->queryType . Brackets::curlies($this->nodes->render());

        return $query;
    }
    public function setCursor()
    {
        $this->nodes->isCursored = true;
    }
    public function setMutation($mutation)
    {
        $this->mutation = $mutation;
        $this->mutation->setDefaults($this->nodes);
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
    public function setReturnField($name, $fields)
    {
        $this->nodes->setReturnField($name, $fields);
    }
};
