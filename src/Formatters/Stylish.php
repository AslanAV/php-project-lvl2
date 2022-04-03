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
    $factor = 0;
    return getFormatStylish($ast, $factor);
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
 * @param int $factor
 * @return string
 */
function buildBody(array $ast, int $factor): string
{
    $result = array_map(function ($node) use ($factor) {
        $value = normalizeValue(getValue($node), $factor);
        $endOfLine = getKey($node) . ":" . SPACE . $value;
        switch (getType($node)) {
            case "unchanged":
            case "hasChildren":
                return getUnchangedIndent($factor) . $endOfLine;
            case "added":
                return getAddedIndent($factor) . $endOfLine;
            case "deleted":
                return getDeletedIndent($factor) . $endOfLine;
            case "changed":
                $firstContent = getDeletedIndent($factor) . $endOfLine;
                $secondValue = normalizeValue(getSecondValue($node), $factor);
                $secondContent = getAddedIndent($factor) . getKey($node) . ":" . SPACE . $secondValue;
                return $firstContent . "\n" . $secondContent;
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
    return str_repeat(getIndent(), $factor) . SPACE . SPACE . ADDED_SYMBOL . SPACE;
}

/**
 * @param int $factor
 * @return string
 */
function getDeletedIndent(int $factor): string
{
    return str_repeat(getIndent(), $factor) . SPACE . SPACE . DELETED_SYMBOL . SPACE;
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
 * @param mixed $node
 * @param int $factor
 * @return string
 */
function normalizeValue($node, int $factor): string
{
     return (is_array($node)) ?
         getFormatStylish($node, $factor + 1) :
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
