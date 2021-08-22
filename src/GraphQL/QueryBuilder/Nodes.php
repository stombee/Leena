<?php

namespace Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder;

use Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder\Utils\Brackets;


class Nodes
{
    public $fields;
    public $attributes;
    public $text;


    public function __construct()
    {
    }
    public function edges($middleValue)
    {
        return "edges { node {" . $middleValue . '} }';
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
        return $this->edges(implode(' ', $this->fields[$key]));
    }
    public function renderFieldsbySingleMode($key)
    {
        return $this->edges(implode(' ', $this->fields[$key]));
    }
    public function render($mode = "bulk")
    {
        $names = "";
        foreach ($this->attributes as $key => $attrs) {
            foreach ($attrs as $name => $value) {
                $names .= $name . ':' . $value . ',';
            }
            $rnames = substr($names, 0, strlen($names) - 1);
            $this->text .= $key . Brackets::parentheses($rnames);
            if ($mode == "single") {
                $this->text .= Brackets::curlies($this->renderFieldsbySingleMode($key));
            } else {
                $this->text .= Brackets::curlies($this->renderFields($key));
            }
        }
        return $this->text;
    }
}
