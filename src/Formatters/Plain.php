<?php

namespace Differ\Formatters\Plain;

use Exception;

use function Differ\BuildAst\type;
use function Differ\BuildAst\key;
use function Differ\BuildAst\value;
use function Differ\BuildAst\children;
use function Differ\BuildAst\secondValue;

const STARTSTRING = "Property ";

/**
 * @param array<mixed> $ast
 * @param string $parent
 * @return string
 */
function formatedToPlain(array $ast, string $parent = ''): string
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
        $key = ($parent !== '') ? $parent . "." . key($node) :  $parent . key($node);
        switch (type($node)) {
            case "hasChildren":
                $newParent = ($parent !== '') ? $parent . "." . key($node) :  $parent . key($node);
                return formatedToPlain(children($node), $newParent);
            case "added":
                return STARTSTRING . keyWithBrace($key) . addedPhrase() . plainValue($node);
            case "deleted":
                return STARTSTRING . keyWithBrace($key) . deletedPhrase();
            case "changed":
                $value = plainValue($node) . " to " . plainSecondValue($node);
                return STARTSTRING . keyWithBrace($key) . changedPhrase() . $value;
            case "unchanged":
                break;
            default:
                throw new Exception("Not support key" . type($node));
        }
    }, $ast);
    $filteredResult = array_filter($result);
    return implode("\n", $filteredResult);
}

/**
 * @param string $key
 * @return string
 */
function keyWithBrace(string $key): string
{
    return  "'" . $key . "'";
}

/**
 * @return string
 */
function addedPhrase(): string
{
    return  " was added with value: ";
}

/**
 * @return string
 */
function deletedPhrase(): string
{
    return  " was removed";
}

/**
 * @return string
 */
function changedPhrase(): string
{
    return " was updated. From ";
}

/**
 * @param array<mixed> $node
 * @return string
 */
function plainValue(array $node): string
{
    $value = value($node);
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
    $value = secondValue($node);
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
