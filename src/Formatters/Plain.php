<?php

namespace Differ\Formatters\Plain;

use Exception;

use function Differ\BuildAst\getType;
use function Differ\BuildAst\getKey;
use function Differ\BuildAst\getValue;
use function Differ\BuildAst\getChildren;
use function Differ\BuildAst\getSecondValue;

const STARTSTRING = "Property ";

/**
 * @param array<mixed> $ast
 * @param string $parent
 * @return string
 */
function formatToPlain(array $ast, string $parent = ''): string
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
                return formatToPlain(getChildren($node), $newParent);
            case "added":
                return STARTSTRING . "'{$key}'" . " was added with value: " . plainValue($node);
            case "deleted":
                return STARTSTRING . "'{$key}'" . " was removed";
            case "changed":
                $value = plainValue($node) . " to " . plainSecondValue($node);
                return STARTSTRING . "'{$key}'" . " was updated. From " . $value;
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
 * @param array<mixed> $node
 * @return string
 */
function plainValue(array $node): string
{
    $value = getValue($node);
    if (is_int($value)) {
        return (string) $value;
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

/**
 * @param array<mixed> $node
 * @return string
 */
function plainSecondValue(array $node): string
{
    $value = getSecondValue($node);
    if (is_int($value)) {
        return (string) $value;
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
