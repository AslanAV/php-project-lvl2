<?php

namespace Hexlet\Code\Differ;

/**
 * @throws \Exception
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
    $fixture = jsonDecode($fileContent);
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
 * @param mixed $fileContent
 * @return mixed
 * @throws \Exception
 */
function jsonDecode($fileContent)
{
    if ($fileContent == false) {
        throw new \Exception("Can't read file");
    }
    return json_decode($fileContent, true);
}

/**
 * @param mixed $fixtures
 * @return mixed
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
 * @param mixed $firstFixtures
 * @param mixed $secondFixtures
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
