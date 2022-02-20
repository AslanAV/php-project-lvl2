<?php

namespace Hexlet\Code\Differ;

function gendiff(string $firstFile, string $secondFile, string $format = 'stylish'): string
{
    $firstFile = fullPathToFile($firstFile);
    $firstFileContent = file_get_contents($firstFile);
    $firstFixtures = jsonDecode($firstFileContent);
    $firstFixtures = normilizeBoolean($firstFixtures);

    $secondFile = fullPathToFile($secondFile);
    $secondFileContent = file_get_contents($secondFile);
    $secondFixtures = jsonDecode($secondFileContent);
    $secondFixtures = normilizeBoolean($secondFixtures);

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
    $ResultWithEnd = "{$result}}\n";
    print_r($ResultWithEnd);
    return $ResultWithEnd;
}

function fullPathToFile(string $file): string
{
    if (substr($file, 1) !== '/') {
        $firstFile = __DIR__ . '/../' . $file;
    }
    return $file;
}

function jsonDecode(string $fileContent): array
{
    if ($fileContent === false) {
        throw new \Exception("Can't read file: " . $fileContent);
    }
    $fixtures = json_decode($fileContent, true);
    return $fixtures;
}

function normilizeBoolean(array $fixtures): array
{
    foreach ($fixtures as $key => $item) {
        if (is_bool($item)) {
            $fixtures[$key] = ($fixtures[$key] === true) ? "true" : "false";
        }
    }
    return $fixtures;
}

