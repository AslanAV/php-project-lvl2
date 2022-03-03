<?php

namespace Hexlet\Code\Formaters\Stylish;

use Exception;

const ADDED = "+";
const DELETED = "-";
const SPACE = " ";

const START = "{\n";
const KEYTOVALUE = ": ";
const END = "}";


function formatedToStylish($ast, $factor = 0)
{
    $end = str_repeat(indent(), $factor) . END;
    return START . buildBody($ast, $factor) . $end;
}

function buildBody($ast, $factor)
{
    //print_r($ast);
    $result = array_reduce($ast, function($acc, $node) use ($factor) {
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
                var_dump($value);
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
    return implode($result, "\n") . "\n";
}

function addedIndent($factor)
{
    return str_repeat(indent(), $factor) . SPACE . SPACE . ADDED . SPACE;
}

function deletedIndent($factor)
{
    return str_repeat(indent(), $factor) . SPACE . SPACE . DELETED . SPACE;
}

function unchangedIndent($factor)
{
    return str_repeat(indent(), $factor) . indent();
}

function indent()
{
    return str_repeat(SPACE, 4);
}

function keyToValue()
{
    return ":" . SPACE;
}

function type($node)
{
    return $node['type'];
}

function key($node)
{
    return $node['key'];
}
function value($node)
{
    return $node['value'];
}
function secondValue($node)
{
    return $node['secondValue'];
}

function children($node)
{
    return $node['value'];
}