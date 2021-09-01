<?php

namespace Erenkucukersoftware\BrugsMigrationTool\GraphQL\QueryBuilder\Utils;

class Brackets
{
    static $curly = ['{', '}'];
    static $parenthese = ['(', ')'];

    private static function place($middleValue, $symbol)
    {
        return $symbol[0] . $middleValue . $symbol[1];
    }
    public static function curlies($middleValue)
    {
        return self::place($middleValue, self::$curly);
    }
    public static function parentheses($middleValue)
    {
        return self::place($middleValue, self::$parenthese);
    }
}
