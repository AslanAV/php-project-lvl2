<?php

namespace Hexlet\Code\Differ;

use Exception;
use Symfony\Component\Yaml\Yaml;

use function Hexlet\Code\Parsers\Parsers\parse;

/**
 * @throws Exception
 */
function gendiff(string $firstFile, string $secondFile, string $format = 'stylish'): string
{
    $firstFixtures = preparationOfFile($firstFile);
    $secondFixtures = preparationOfFile($secondFile);
    $result = diffFixtures($firstFixtures, $secondFixtures);
    print_r($result);
    return $result;
}

/**
 * @param mixed $file
 * @return mixed
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

function fullPathToFile(string $file): string
{
    if (substr($file, 1) !== '/') {
        $file = __DIR__ . '/../' . $file;
    }
    return $file;
}

/**
 * @param string $fileContent
 * @return array<mixed>
 */
function yamlDecode($fileContent)
{
    return Yaml::parse($fileContent);
}

/**
 * @param string $fileContent
 * @return array<mixed>
 */
function jsonDecode($fileContent)
{
    return json_decode($fileContent, true);
}

/**
 * @param array<mixed> $fixtures
 * @return array<mixed>
 */
function normalizeBoolean($fixtures)
{
    foreach ($fixtures as $key => $item) {
        if (is_bool($item)) {
            $fixtures[$key] = ($fixtures[$key] === true) ? "true" : "false";
        }
    }
    return $fixtures;
}

/**
 * @param array<mixed> $firstFixtures
 * @param array<mixed> $secondFixtures
 * @return string
 */
function diffFixtures($firstFixtures, $secondFixtures): string
{
    $keys = array_unique(array_merge(array_keys($firstFixtures), array_keys($secondFixtures)));
    asort($keys);
    $result = array_reduce($keys, function ($acc, $key) use ($firstFixtures, $secondFixtures) {
        if (array_key_exists($key, $firstFixtures) && array_key_exists($key, $secondFixtures)) {
            if ($firstFixtures[$key] === $secondFixtures[$key]) {
                $acc = "{$acc}    {$key}: {$firstFixtures[$key]}\n";
            } else {
                $acc = "{$acc} -  {$key}: {$firstFixtures[$key]}\n";
                $acc = "{$acc} +  {$key}: {$secondFixtures[$key]}\n";
            }
        } elseif (array_key_exists($key, $firstFixtures)) {
            $acc = "{$acc} -  {$key}: {$firstFixtures[$key]}\n";
        } elseif (array_key_exists($key, $secondFixtures)) {
            $acc = "{$acc} +  {$key}: {$secondFixtures[$key]}\n";
        }
        return $acc;
    }, "{\n");
    return "{$result}}\n";
}
