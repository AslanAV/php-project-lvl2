<?php

namespace Differ\Formatters;

use function Differ\Formatters\Plain\format as formatPlain;
use function Differ\Formatters\Stylish\format as formatStylish;
use function Differ\Formatters\Json\format as formatJson;

/**
 * @param array<mixed> $ast
 * @param string $format
 * @return string
 */
function format(array $ast, string $format): string
{
    switch ($format) {
        case "stylish":
            return formatStylish($ast);
        case "plain":
            return formatPlain($ast);
        case "json":
            return formatJson($ast);
        default:
            throw new \Exception('Unknown format: ' . $format);
    }
}
