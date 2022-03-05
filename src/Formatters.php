<?php

namespace Differ\Formatters;

use function Differ\Formatters\Plain\formatedToPlain;
use function Differ\Formatters\Stylish\formatedToStylish;
use function Differ\Formatters\Json\formatedToJson;

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
        case "json":
            return formatedToJson($ast);
        default:
            throw new \Exception('Unknown format: ' . $format);
    }
}
