<?php

namespace Differ\Formatters\Stylish;

use Exception;

use function Differ\BuildAst\getType;
use function Differ\BuildAst\getKey;
use function Differ\BuildAst\getValue;
use function Differ\BuildAst\getChildren;
use function Differ\BuildAst\getSecondValue;

const ADDED_SYMBOL = "+";
const DELETED_SYMBOL = "-";
const SPACE = " ";

const START = "{\n";
const END = "}";

/**
 * @param array<mixed> $ast
 * @return string
 */
function format(array $ast): string
{
    $depth = 0;
    return getFormatStylish($ast, $depth);
}

/**
 * @param array<mixed> $ast
 * @param int $depth
 * @return string
 */
function getFormatStylish(array $ast, int $depth = 0): string
{
    return START . buildBody($ast, $depth) . str_repeat(getIndent(), $depth) . END;
}

/**
 * @param array<mixed> $ast
 * @param int $depth
 * @return string
 */
function buildBody(array $ast, int $depth): string
{
    $result = array_map(function ($node) use ($depth) {
        $value = normalizeValue(getValue($node), $depth);
        $endOfLine = getKey($node) . ":" . SPACE . $value;
        switch (getType($node)) {
            case "unchanged":
            case "hasChildren":
                return getUnchangedIndent($depth) . $endOfLine;
            case "added":
                return getAddedIndent($depth) . $endOfLine;
            case "deleted":
                return getDeletedIndent($depth) . $endOfLine;
            case "changed":
                $firstContent = getDeletedIndent($depth) . $endOfLine;
                $secondValue = normalizeValue(getSecondValue($node), $depth);
                $secondContent = getAddedIndent($depth) . getKey($node) . ":" . SPACE . $secondValue;
                return $firstContent . "\n" . $secondContent;
            default:
                throw new Exception("Not support key" . getType($node));
        }
    }, $ast);
    return implode("\n", $result) . "\n";
}

/**
 * @param int $depth
 * @return string
 */
function getAddedIndent(int $depth): string
{
    return str_repeat(getIndent(), $depth) . SPACE . SPACE . ADDED_SYMBOL . SPACE;
}

/**
 * @param int $depth
 * @return string
 */
function getDeletedIndent(int $depth): string
{
    return str_repeat(getIndent(), $depth) . SPACE . SPACE . DELETED_SYMBOL . SPACE;
}

/**
 * @param int $depth
 * @return string
 */
function getUnchangedIndent(int $depth): string
{
    return str_repeat(getIndent(), $depth) . getIndent();
}

/**
 * @return string
 */
function getIndent(): string
{
    return str_repeat(SPACE, 4);
}


/**
 * @param mixed $node
 * @param int $depth
 * @return string
 */
function normalizeValue($node, int $depth): string
{
     return (is_array($node)) ?
         getFormatStylish($node, $depth + 1) :
         normalizeBooleanAndNull($node);
}

/**
 * @param mixed $contents
 * @return mixed
 */
function normalizeBooleanAndNull($contents)
{
    if (is_null($contents)) {
        return "null";
    }

    if (is_bool($contents)) {
        return ($contents === true) ? "true" : "false";
    }
    return $contents;
}
