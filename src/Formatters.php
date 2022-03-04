<?php

namespace Hexlet\Code\Formatters;

use function Hexlet\Code\Formatters\Plain\formatedToPlain;
use function Hexlet\Code\Formatters\Stylish\formatedToStylish;

/**
 * @param array<mixed> $ast
 * @param string $format
 * @return string
 */
function formatToString($ast, $format)
{
    switch ($format) {
        case "stylish":
            return formatedToStylish($ast);
        case "plain":
            return formatedToPlain($ast);
        default:
            throw new \Exception('Unknown format: ' . $format);
    }
}
