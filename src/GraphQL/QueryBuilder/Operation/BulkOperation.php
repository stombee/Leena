<?php

namespace Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder\Operation;

use Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder\Operation\Operation;
use Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder\Utils\Brackets;
use Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder\Utils\Quoted;


class BulkOperation extends Operation
{
    public function setDefaults()
    {
        $this->addFields(["bulkOperationRunMutation"]);
        $this->addAttribute("bulkOperationRunMutation", "mutation", new Quoted("mutation call(\$input: ProductInput!) { productUpdate(input: \$input) { product {id} userErrors { message field } } }"));
        $this->addAttribute("bulkOperationRunMutation", "stagedUploadPath", new Quoted("tmp/55055417496/bulk/6ced99fb-9ba2-48ca-b6b5-ad4467b79ed4/sometry"));
        $this->addSubFields('bulkOperationRunMutation', ['bulkOperation.id', 'bulkOperation.url', 'bulkOperation.status']);
        $this->addSubFields('bulkOperationRunMutation', ['userErrors.message', 'userErrors.field']);
    }

    public function render()
    {
        $this->setDefaults();
        $query = "";
        $query .= $this->queryType . Brackets::curlies($this->nodes->render());

        return $query;
    }
}
