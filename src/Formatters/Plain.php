<?php

namespace Differ\Formatters\Plain;

use Exception;

use function Differ\BuildAst\getType;
use function Differ\BuildAst\getKey;
use function Differ\BuildAst\getValue;
use function Differ\BuildAst\getChildren;
use function Differ\BuildAst\getSecondValue;

/**
 * @param array<mixed> $ast
 * @return string
 */
function format(array $ast): string
{
    return buildBody($ast);
}

/**
 * @param array<mixed> $ast
 * @param string $parent
 * @return string
 */
function buildBody(array $ast, string $parent = ''): string
{
    $result = array_map(function ($node) use ($parent) {
        $key = ($parent !== '') ? $parent . "." . getKey($node) :  $parent . getKey($node);
        switch (getType($node)) {
            case "hasChildren":
                $newParent = ($parent !== '') ? $parent . "." . getKey($node) :  $parent . getKey($node);
                return buildBody(getChildren($node), $newParent);
            case "added":
                $value = getPlainValue(getValue($node));
                return "Property '{$key}' was added with value: {$value}";
            case "deleted":
                return "Property '{$key}' was removed";
            case "changed":
                $firstValue = getPlainValue(getValue($node));
                $secondValue = getPlainValue(getSecondValue($node));
                return "Property '{$key}' was updated. From {$firstValue} to {$secondValue}";
            case "unchanged":
                break;
            default:
                throw new Exception("Not support key" . getType($node));
        }
    }, $ast);
    $filteredResult = array_filter($result);
    return implode("\n", $filteredResult);
}

/**
 * @param mixed $value
 * @return mixed
 */
function getPlainValue($value)
{
    if (is_int($value)) {
        return  (string) $value;
    }

    if (is_array($value)) {
        return "[complex value]";
    }

    if (is_null($value)) {
        return "null";
    }

    if (is_bool($value)) {
        return ($value === true) ? "true" : "false";
    }

    return  "'" . $value . "'";
}
