<?php

namespace Differ\Formatters;

use function Differ\Formatters\Plain\formatToPlain;
use function Differ\Formatters\Stylish\formatToStylish;
use function Differ\Formatters\Json\formatToJson;

/**
 * @param array<mixed> $ast
 * @param string $format
 * @return string
 */
function formatToString(array $ast, string $format): string
{
    switch ($format) {
        case "stylish":
            return formatToStylish($ast);
        case "plain":
            return formatToPlain($ast);
        case "json":
            return formatToJson($ast);
        default:
            throw new \Exception('Unknown format: ' . $format);
    }
}
