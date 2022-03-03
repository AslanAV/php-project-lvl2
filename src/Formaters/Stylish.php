<?php

namespace Hexlet\Code\Formaters\Stylish;

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
    //print_r($ast);
    $result = array_reduce($ast, function ($acc, $node) use ($factor) {
        $value = (is_array(value($node))) ? formatedToStylish(children($node), $factor + 1) : value($node);
        switch (type($node)) {
            case "hasChildren":
                $acc[] = unchangedIndent($factor) . key($node) . keyToValue() . $value;
                break;
            case "added":
                $acc[] = addedIndent($factor) . key($node) . keyToValue() . $value;
                break;
            case "deleted":
                $acc[] = deletedIndent($factor) . key($node) . keyToValue() . $value;
                break;
            case "changed":
//                var_dump(value($node, $factor));
                $acc[] = deletedIndent($factor) . key($node) . keyToValue() . $value;
                $acc[] = addedIndent($factor) . key($node) . keyToValue() . secondValue($node);
                break;
            case "unchanged":
                $acc[] = unchangedIndent($factor) . key($node) . keyToValue() . $value;
                break;
            default:
                throw new Exception("Not support key" . type($node));
        }
        return $acc;
    }, []);
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
