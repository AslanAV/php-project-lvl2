<?php

 namespace Hexlet\Code\Differ;

use Exception;

use function Hexlet\Code\Parsers\Parsers\parse;
use function Hexlet\Code\BuildAst\buildAst;
use function Hexlet\Code\Formaters\Stylish\formatedToStylish;

/**
 * @param string $firstFile
 * @param string $secondFile
 * @param string $format
 * @return
 */
function genDiff(string $firstFile, string $secondFile, string $format = 'stylish')
{
    $firstFixtures = preparationOfFile($firstFile);
    $secondFixtures = preparationOfFile($secondFile);
    //var_dump($firstFixtures);
    //var_dump($secondFixtures);
    $ast = buildAst($firstFixtures, $secondFixtures);
    $result = formatedToStylish($ast);
    //print_r($result);
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
    return normalizeBoolean($fixture);
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
function normalizeBoolean(array $fixtures): array
{
    foreach ($fixtures as $key => $item) {
        if (is_array($fixtures[$key])) {
            $fixtures[$key] = normalizeBoolean($item);
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
