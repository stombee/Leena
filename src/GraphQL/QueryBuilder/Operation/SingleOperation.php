<?php

namespace Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder\Operation;

use Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder\Operation\Operation;
use Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder\Utils\Brackets;


class SingleOperation extends Operation
{
    public function render()
    {

        $query = "";
        $query .= $this->queryType . Brackets::curlies($this->nodes->render("single"));

        return $query;
    }
}
