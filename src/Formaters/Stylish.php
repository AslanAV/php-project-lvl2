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
        switch (type($node)) {
            case "hasChildren":
                $acc[] = unchangedIndent($factor) . key($node) . keyToValue() . value($node, $factor);
                break;
            case "added":
                $acc[] = addedIndent($factor) . key($node) . keyToValue() . value($node, $factor);
                break;
            case "deleted":
                $acc[] = deletedIndent($factor) . key($node) . keyToValue() . value($node, $factor);
                break;
            case "changed":
//                var_dump(value($node, $factor));
                $acc[] = deletedIndent($factor) . key($node) . keyToValue() . value($node, $factor);
                $acc[] = addedIndent($factor) . key($node) . keyToValue() . secondValue($node);
                break;
            case "unchanged":
                $acc[] = unchangedIndent($factor) . key($node) . keyToValue() . value($node, $factor);
                break;
            default:
                throw new Exception("Not support key" . type($node));
        }
        return $acc;
    }, []);
    return implode("\n" ,$result) . "\n";
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
function value($node, $factor)
{
    return (is_array($node['value'])) ? formatedToStylish(children($node), $factor + 1) : $node['value'];
}
function secondValue($node)
{
    return $node['secondValue'];
}

function children($node)
{
    return $node['value'];
}