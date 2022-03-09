<?php

namespace Differ\Formatters\Stylish;

use Exception;

use function Differ\BuildAst\getType;
use function Differ\BuildAst\getKey;
use function Differ\BuildAst\getValue;
use function Differ\BuildAst\getChildren;
use function Differ\BuildAst\getSecondValue;

const ADDED = "+";
const DELETED = "-";
const SPACE = " ";

const START = "{\n";
const END = "}";

/**
 * @param array<mixed> $ast
 * @param int $factor
 * @return string
 */
function formatToStylish(array $ast, int $factor = 0): string
{
    return START . buildBody($ast, $factor) . getEndofStylish($factor);
}

/**
 * @param array<mixed> $ast
 * @param int $factor
 * @return string
 */
function buildBody(array $ast, int $factor): string
{
    $result = array_map(function ($node) use ($factor) {
        $value = normalizedValue($node, $factor);
        switch (getType($node)) {
            case "hasChildren":
                return getUnchangedIndent($factor) . getKey($node) . getIndentkeyToValue() . $value;
            case "added":
                return getAddedIndent($factor) . getKey($node) . getIndentkeyToValue() . $value;
            case "deleted":
                return getDeletedIndent($factor) . getKey($node) . getIndentkeyToValue() . $value;
            case "changed":
                $firstContent = getDeletedIndent($factor) . getKey($node) . getIndentkeyToValue() . $value;
                $secondValue = normalizedSecondValue(getSecondValue($node), $factor);
                $secondContent = getAddedIndent($factor) . getKey($node) . getIndentkeyToValue() . $secondValue;
                return $firstContent . "\n" . $secondContent;
            case "unchanged":
                return getUnchangedIndent($factor) . getKey($node) . getIndentkeyToValue() . $value;
            default:
                throw new Exception("Not support key" . getType($node));
        }
    }, $ast);
    return implode("\n", $result) . "\n";
}

/**
 * @param int $factor
 * @return string
 */
function getAddedIndent(int $factor): string
{
    return str_repeat(getIndent(), $factor) . SPACE . SPACE . ADDED . SPACE;
}

/**
 * @param int $factor
 * @return string
 */
function getDeletedIndent(int $factor): string
{
    return str_repeat(getIndent(), $factor) . SPACE . SPACE . DELETED . SPACE;
}

/**
 * @param int $factor
 * @return string
 */
function getUnchangedIndent(int $factor): string
{
    return str_repeat(getIndent(), $factor) . getIndent();
}

/**
 * @return string
 */
function getIndent(): string
{
    return str_repeat(SPACE, 4);
}

/**
 * @return string
 */
function getIndentkeyToValue(): string
{
    return ":" . SPACE;
}

/**
 * @param mixed $node
 * @param int $factor
 * @return string
 */
function normalizedValue($node, int $factor): string
{
     return (is_array(getValue($node))) ?
         formatToStylish(getChildren($node), $factor + 1) :
         getValue($node);
}

/**
 * @param mixed $node
 * @param int $factor
 * @return string
 */
function normalizedSecondValue($node, int $factor): string
{
    return (is_array($node)) ?
        formatToStylish($node, $factor + 1) :
        $node;
}

/**
 * @param int $factor
 * @return string
 */
function getEndofStylish(int $factor): string
{
    return str_repeat(getIndent(), $factor) . END;
}
