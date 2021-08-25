<?php

namespace Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder;

use Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder\Utils\Brackets;


class QueryMode
{
    protected $nodes;

    public function __construct(&$nodes)
    {
        $this->nodes = $nodes;
    }
    public function render($key)
    {
    }
}
class SingleMode extends QueryMode
{
    public function render($key)
    {
        if ($this->nodes->returnFields == null) {

            return Brackets::curlies($this->nodes->renderFieldsbySingleMode($key));
        } else {
            foreach ($this->nodes->returnFields as $key => $attrs) {
                return Brackets::curlies("... on " . $key . Brackets::curlies($this->nodes->renderReturnFields($key)));
            }
        }
    }
}
class BulkMode extends QueryMode
{
    public function render($key)
    {

        return Brackets::curlies($this->nodes->renderFields($key));
    }
}

class Nodes
{
    public $fields;
    public $attributes;
    public $text;
    public $returnFields;
    public $queryMode;
    public $isCursored;


    public function setMode($mode = "bulk")
    {
        if ($mode == "single") {
            $this->queryMode = new SingleMode($this);
        } else {
            $this->queryMode = new BulkMode($this);
        }
    }

    public function edges($middleValue)
    {
        $cursor = "";
        if ($this->isCursored) {
            $cursor = "cursor";
        } else {
            $cursor = "";
        }
        return "edges { " . $cursor . " node {" . $middleValue . '} }';
    }
    public function addField($name, $value)
    {
        $this->fields[$name][] = $value;
    }
    public function addFields($fields)
    {
        foreach ($fields as $item) {
            $item = explode('.', $item);
            if (count($item) == 2) {
                $this->addField($item[0], $item[1]);
            } else {
                $this->addField($item[0], "");
            }
        }
    }
    public function setReturnField($name, $fields)
    {
        foreach ($fields as $value) {
            $this->returnFields[$name][] = $value;
        }
    }
    public function findAndAddSubField(&$items, $field, $subFields)
    {
        foreach ($items as $item => $value) {
            if (!is_array($item) && !is_object($item)) {
                if ($item == $field) {
                    foreach ($subFields as $sub) {
                        $sub = explode('.', $sub);
                        $items[$item][$sub[0]][] = $sub[1];
                    }
                }
            } else {
                $this->findAndAddSubField($item, $field, $subFields);
            }
        }
    }
    public function addSubFields($field, $subFields)
    {
        $this->findAndAddSubField($this->fields, $field, $subFields);
    }
    public function addAttribute($name, $attribute, $value)
    {
        $this->attributes[$name][$attribute] = $value;
        if ($this->fields == null) {
            $this->addFields([$name]);
        }
    }
    public function renderReturnFields($key)
    {
        $fieldText = "";
        foreach ($this->returnFields[$key] as $key => $value) {

            if (!is_array($value)) {
                $fieldText .= $value . ' ';
            } else {
                $fieldText .= $key . Brackets::curlies(implode(' ', $value));
            }
        }
        return $fieldText;
    }
    public function renderFields($key)
    {
        $fieldText = "";
        foreach ($this->fields[$key] as $key => $value) {

            if (!is_array($value)) {
                $fieldText .= $value . ' ';
            } else {
                $fieldText .= $key . Brackets::curlies(implode(' ', $value));
            }
        }
        return $fieldText;
    }
    public function renderFieldsbySingleMode($key)
    {
        return $this->edges(implode(' ', $this->fields[$key]));
    }
    public function render()
    {
        $names = "";
        foreach ($this->attributes as $key => $attrs) {
            foreach ($attrs as $name => $value) {
                $names .= $name . ':' . $value . ',';
            }
            $rnames = substr($names, 0, strlen($names) - 1);
            $this->text .= $key . Brackets::parentheses($rnames);
            $this->text .=
                $this->queryMode->render($key);
        }
        return $this->text;
    }
}
