<?php

namespace Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder\Utils;

class Quoted
{
    private $value;
    public function __construct($value)
    {
        $this->value = $value;
    }
    public function __toString()
    {
        return '"' . $this->value . '"';
    }
}
