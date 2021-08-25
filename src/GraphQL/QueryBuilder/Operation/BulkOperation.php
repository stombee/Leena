<?php

namespace Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder\Operation;

use Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder\Operation\Operation;
use Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder\Utils\Brackets;
use Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder\Utils\Quoted;
use Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder\Nodes;


class BulkOperation extends Operation
{
    public function __construct()
    {
        parent::__construct();
        $this->nodes->setMode("bulk");
    }
    public function addFields(array $fields)
    {
        $rootField = $this->mutation->getRootField();
        $this->addSubFields($rootField, $fields);
    }
    public function render()
    {
        $query = "";
        $query .= $this->queryType . Brackets::curlies($this->nodes->render());

        return $query;
    }
}
