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
    foreach ($fixtures as $key => $item) {
        if (is_array($item)) {
            $fixtures[$key] = normalizeBooleanAndNull($item);
        }
        if (is_null($item)) {
            $fixtures[$key] = "null";
        }
        if (is_bool($item)) {
            $fixtures[$key] = ($fixtures[$key] === true) ? "true" : "false";
        }
    }
    return $fixtures;
}
