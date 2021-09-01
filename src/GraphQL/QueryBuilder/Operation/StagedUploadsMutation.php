<?php

namespace Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder\Operation;

use Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder\Operation\BulkOperation;
use Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder\Utils\Brackets;
use Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder\Utils\Quoted;


class StagedUploadsMutation
{
    public function setDefaults($nodes)
    {
        $nodes->addFields(["stagedUploadsCreate"]);
        $nodes->addAttribute("stagedUploadsCreate", "input", '{
                resource: BULK_MUTATION_VARIABLES,
                filename: "sometry",
                mimeType: "text/jsonl",
                httpMethod: POST
                }');
        // $nodes->addAttribute("stagedUploadsCreate", "resource", "BULK_MUTATION_VARIABLES");
        // $nodes->addAttribute("stagedUploadsCreate", "filename", "sometry");
        // $nodes->addAttribute("stagedUploadsCreate", "mimeType", "text/jsonl");
        // $nodes->addAttribute("stagedUploadsCreate", "httpMethod", "POST");
    }
    public function getRootField()
    {
        return "stagedUploadsCreate";
    }
}
