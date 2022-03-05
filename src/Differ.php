<?php

namespace Differ\Differ;

use Exception;

use function Differ\Parsers\Parsers\parse;
use function Differ\BuildAst\buildAst;
use function Differ\Formatters\formatToString;

/**
 * @param string $firstFile
 * @param string $secondFile
 * @param string $format
 * @return string
 */
function genDiff($firstFile, $secondFile, $format = 'stylish')
{
    $firstFixtures = preparationOfFile($firstFile);
    $secondFixtures = preparationOfFile($secondFile);
    $ast = buildAst($firstFixtures, $secondFixtures);
    return formatToString($ast, $format);
}

/**
 * @param string $file
 * @return array<mixed>
 */
function preparationOfFile($file)
{
    $fileWithFullPath = fullPathToFile($file);
    $fileContent = file_get_contents($fileWithFullPath);
    if ($fileContent == false) {
        throw new Exception("Can't read file");
    }
    $fixture = parse($fileWithFullPath, $fileContent);
    return normalizeBooleanAndNull($fixture);
}

/**
 * @param string $file
 * @return string
 */
function fullPathToFile($file)
{
    if (strpos($file, '/') === 0) {
        return $file;
    }

    return __DIR__ . '/../' . $file;
}

/**
 * @param array<mixed> $fixtures
 * @return array<mixed>
 */
function normalizeBooleanAndNull($fixtures)
{
    return array_map(function ($item) {
        if (is_array($item)) {
            return normalizeBooleanAndNull($item);
        }
        if (is_null($item)) {
            return "null";
        }
        if (is_bool($item)) {
            return ($item === true) ? "true" : "false";
        }
        return $item;
    }, $fixtures);
}
