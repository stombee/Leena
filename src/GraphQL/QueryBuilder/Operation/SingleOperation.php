<?php

namespace Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder\Operation;

use Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder\Operation\Operation;
use Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder\Utils\Brackets;
use Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder\Nodes;


class SingleOperation extends Operation
{
    public function __construct()
    {
        parent::__construct();
        $this->nodes->setMode("single");
    }
    public function render()
    {
        $query = "";
        $query .= $this->queryType . Brackets::curlies($this->nodes->render("single"));

        return $query;
    }
}
