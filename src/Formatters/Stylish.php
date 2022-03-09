<?php

namespace Differ\Formatters\Stylish;

use Exception;

use function Differ\BuildAst\type;
use function Differ\BuildAst\key;
use function Differ\BuildAst\value;
use function Differ\BuildAst\children;
use function Differ\BuildAst\secondValue;

const ADDED = "+";
const DELETED = "-";
const SPACE = " ";

const START = "{\n";
const KEYTOVALUE = ": ";
const END = "}";

/**
 * @param array<mixed> $ast
 * @param int $factor
 * @return string
 */
function formatedToStylish(array $ast, int $factor = 0): string
{
    $end = str_repeat(indent(), $factor) . END;
    return START . buildBody($ast, $factor) . $end;
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
        switch (type($node)) {
            case "hasChildren":
                return unchangedIndent($factor) . key($node) . keyToValue() . $value;
            case "added":
                return addedIndent($factor) . key($node) . keyToValue() . $value;
            case "deleted":
                return deletedIndent($factor) . key($node) . keyToValue() . $value;
            case "changed":
                $firstContent = deletedIndent($factor) . key($node) . keyToValue() . $value;
                $secondValue = normalizedSecondValue(secondValue($node), $factor);
                $secondContent = addedIndent($factor) . key($node) . keyToValue() . $secondValue;
                return $firstContent . "\n" . $secondContent;
            case "unchanged":
                return unchangedIndent($factor) . key($node) . keyToValue() . $value;
            default:
                throw new Exception("Not support key" . type($node));
        }
    }, $ast);
    return implode("\n", $result) . "\n";
}

/**
 * @param int $factor
 * @return string
 */
function addedIndent(int $factor): string
{
    return str_repeat(indent(), $factor) . SPACE . SPACE . ADDED . SPACE;
}

/**
 * @param int $factor
 * @return string
 */
function deletedIndent(int $factor): string
{
    return str_repeat(indent(), $factor) . SPACE . SPACE . DELETED . SPACE;
}

/**
 * @param int $factor
 * @return string
 */
function unchangedIndent(int $factor): string
{
    return str_repeat(indent(), $factor) . indent();
}

/**
 * @return string
 */
function indent(): string
{
    return str_repeat(SPACE, 4);
}

/**
 * @return string
 */
function keyToValue(): string
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
     return (is_array(value($node))) ?
         formatedToStylish(children($node), $factor + 1) :
         value($node);
}

/**
 * @param mixed $node
 * @param int $factor
 * @return string
 */
function normalizedSecondValue($node, int $factor): string
{
    return (is_array($node)) ?
        formatedToStylish($node, $factor + 1) :
        $node;
}
