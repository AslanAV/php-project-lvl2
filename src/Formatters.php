<?php

namespace Hexlet\Code\Formatters;

use function Hexlet\Code\Formatters\Plain\formatedToPlain;
use function Hexlet\Code\Formatters\Stylish\formatedToStylish;
use function Hexlet\Code\Formatters\Json\formatedToJson;

/**
 * @param array<mixed> $ast
 * @param string $format
 * @return string
 */
function formatToString($ast, $format)
{
    $format = mb_strtolower($format);
    switch ($format) {
        case "stylish":
            return formatedToStylish($ast);
        case "plain":
            return formatedToPlain($ast);
        case "json":
            return formatedToJson($ast);
        default:
            throw new \Exception('Unknown format: ' . $format);
    }
}
