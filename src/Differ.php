<?php

 namespace Hexlet\Code\Differ;

use Exception;

use function Hexlet\Code\Parsers\Parsers\parse;
use function Hexlet\Code\BuildAst\buildAst;
use function Hexlet\Code\Formatters\formatToString;

/**
 * @param string $firstFile
 * @param string $secondFile
 * @param string $format
 * @return string
 */
function genDiff(string $firstFile, string $secondFile, string $format = 'stylish'): string
{
    $firstFixtures = preparationOfFile($firstFile);
    $secondFixtures = preparationOfFile($secondFile);
    $ast = buildAst($firstFixtures, $secondFixtures);
    $result = formatToString($ast, $format);
    return $result;
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
function fullPathToFile(string $file): string
{
    if (substr($file, 1) !== '/') {
        $file = __DIR__ . '/../' . $file;
    }
    return $file;
}

/**
 * @param array<mixed> $fixtures
 * @return array<mixed>
 */
function normalizeBooleanAndNull(array $fixtures): array
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
