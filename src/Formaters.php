<?php

namespace Hexlet\Code\Formaters;

use function Hexlet\Code\Formaters\Stylish\formatedToStylish;

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
//        case "plain":
//            return;
        default:
            throw new \Exception('Unknown format: ' . $format);
    }
}
