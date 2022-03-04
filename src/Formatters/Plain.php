<?php

namespace Hexlet\Code\Formatters\Plain;

use Exception;

use function Hexlet\Code\BuildAst\type;
use function Hexlet\Code\BuildAst\key;
use function Hexlet\Code\BuildAst\value;
use function Hexlet\Code\BuildAst\children;
use function Hexlet\Code\BuildAst\secondValue;

const STARTSTRING = "Property ";

/**
 * @param array<mixed> $ast
 * @param string $parent
 * @return string
 */
function formatedToPlain($ast, $parent = '')
{
    if ($parent === '') {
        return buildBody($ast, $parent) . "\n";
    }
    return buildBody($ast, $parent);
}

/**
 * @param array<mixed> $ast
 * @param string $parent
 * @return string
 */
function buildBody($ast, $parent)
{
    $result = array_reduce($ast, function ($acc, $node) use ($parent) {
        $key = ($parent !== '') ? $parent . "." . key($node) :  $parent . key($node);
        switch (type($node)) {
            case "hasChildren":
                $newParent = ($parent !== '') ? $parent . "." . key($node) :  $parent . key($node);
                $acc[] = formatedToPlain(children($node), $newParent);
                break;
            case "added":
                $acc[] = STARTSTRING . keyWithBrace($key) . addedPhrase() . plainValue($node);
                break;
            case "deleted":
                $acc[] = STARTSTRING . keyWithBrace($key) . deletedPhrase();
                break;
            case "changed":
                $value = plainValue($node) . " to " . plainSecondValue($node);
                $acc[] = STARTSTRING . keyWithBrace($key) . changedPhrase() . $value;
                break;
            case "unchanged":
                break;
            default:
                throw new Exception("Not support key" . type($node));
        }
        return $acc;
    }, []);
    return implode("\n", $result);
}

/**
 * @param string $key
 * @return string
 */
function keyWithBrace($key)
{
    return  "'" . $key . "'";
}

/**
 * @return string
 */
function addedPhrase()
{
    return  " was added with value: ";
}

/**
 * @return string
 */
function deletedPhrase()
{
    return  " was removed";
}

/**
 * @return string
 */
function changedPhrase()
{
    return " was updated. From ";
}

/**
 * @param array<mixed> $node
 * @return string
 */
function plainValue($node)
{
    $value = value($node);
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
function plainSecondValue($node)
{
    $value = secondValue($node);
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
