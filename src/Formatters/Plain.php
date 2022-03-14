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
    $parent = '';
    return getFormatPlain($ast, $parent);
}

/**
 * @param array<mixed> $ast
 * @param string $parent
 * @return string
 */
function getFormatPlain(array $ast, string $parent = ''): string
{
    return buildBody($ast, $parent);
}

/**
 * @param array<mixed> $ast
 * @param string $parent
 * @return string
 */
function buildBody(array $ast, string $parent): string
{
    $result = array_map(function ($node) use ($parent) {
        $key = ($parent !== '') ? $parent . "." . getKey($node) :  $parent . getKey($node);
        switch (getType($node)) {
            case "hasChildren":
                $newParent = ($parent !== '') ? $parent . "." . getKey($node) :  $parent . getKey($node);
                return getFormatPlain(getChildren($node), $newParent);
            case "added":
                $value = getPlainValue(getValue($node));
                return "Property " . "'{$key}'" . " was added with value: " . $value;
            case "deleted":
                return "Property " . "'{$key}'" . " was removed";
            case "changed":
                $firstValue = getPlainValue(getValue($node));
                $secondValue = getPlainValue(getSecondValue($node));
                $value = $firstValue . " to " . $secondValue;
                return "Property " . "'{$key}'" . " was updated. From " . $value;
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
 * @param array<mixed>|string|int $value
 * @return string
 */
function getPlainValue($value): string
{
    if (is_int($value)) {
        return  (string) $value;
    }
    if (is_array($value)) {
        return "[complex value]";
    }
    switch ($value) {
        case "false":
        case "true":
        case "null":
            return $value;
        default:
            return  "'" . $value . "'";
    }
}
