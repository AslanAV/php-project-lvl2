<?php

namespace Hexlet\Code\Formatters\Stylish;

use Exception;

use function Hexlet\Code\BuildAst\type;
use function Hexlet\Code\BuildAst\key;
use function Hexlet\Code\BuildAst\value;
use function Hexlet\Code\BuildAst\children;
use function Hexlet\Code\BuildAst\secondValue;

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
function formatedToStylish($ast, $factor = 0)
{
    switch ($factor) {
        case 0:
            $end = str_repeat(indent(), $factor) . END . "\n";
            break;
        default:
            $end = str_repeat(indent(), $factor) . END;
    }
    return START . buildBody($ast, $factor) . $end;
}

/**
 * @param array<mixed> $ast
 * @param int $factor
 * @return string
 */
function buildBody($ast, $factor)
{
    $result = array_map(function ($node) use ($factor) {
        $value = (is_array(value($node))) ? formatedToStylish(children($node), $factor + 1) : value($node);
        switch (type($node)) {
            case "hasChildren":
                return unchangedIndent($factor) . key($node) . keyToValue() . $value;
            case "added":
                return addedIndent($factor) . key($node) . keyToValue() . $value;
            case "deleted":
                return deletedIndent($factor) . key($node) . keyToValue() . $value;
            case "changed":
                $firstContent = deletedIndent($factor) . key($node) . keyToValue() . $value;
                $secondContent = addedIndent($factor) . key($node) . keyToValue() . secondValue($node);
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
function addedIndent($factor)
{
    return str_repeat(indent(), $factor) . SPACE . SPACE . ADDED . SPACE;
}

/**
 * @param int $factor
 * @return string
 */
function deletedIndent($factor)
{
    return str_repeat(indent(), $factor) . SPACE . SPACE . DELETED . SPACE;
}

/**
 * @param int $factor
 * @return string
 */
function unchangedIndent($factor)
{
    return str_repeat(indent(), $factor) . indent();
}

/**
 * @return string
 */
function indent()
{
    return str_repeat(SPACE, 4);
}

/**
 * @return string
 */
function keyToValue()
{
    return ":" . SPACE;
}
