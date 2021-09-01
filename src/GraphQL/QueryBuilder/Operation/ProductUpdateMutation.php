<?php

namespace Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder\Operation;

use Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder\Operation\BulkOperation;
use Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder\Utils\Brackets;
use Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder\Utils\Quoted;


class ProductUpdateMutation
{
    public function setDefaults($nodes)
    {

        $nodes->addFields(["bulkOperationRunMutation"]);
        $nodes->addAttribute("bulkOperationRunMutation", "mutation", new Quoted("mutation call(\$input: ProductInput!) { productUpdate(input: \$input) { product {id} userErrors { message field } } }"));
        $nodes->addAttribute("bulkOperationRunMutation", "stagedUploadPath", new Quoted("tmp/55055417496/bulk/6ced99fb-9ba2-48ca-b6b5-ad4467b79ed4/sometry"));
        $nodes->addSubFields('bulkOperationRunMutation', ['bulkOperation.id', 'bulkOperation.url', 'bulkOperation.status']);
        $nodes->addSubFields('bulkOperationRunMutation', ['userErrors.message', 'userErrors.field']);
    }
    public function getRootField()
    {
        return "bulkOperationRunMutation";
    }
}
