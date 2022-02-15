<?php

namespace Hexlet\Code\Differ;

function gendiff(string $firstFile, string $secondFile, string $format = 'stylish'):string
{
    $firstFileContent = file_get_contents($firstFile);
    $firstFixtures = json_decode($firstFileContent, true);

    $secondFileContent = file_get_contents($secondFile);
    $secondFixtures = json_decode($secondFileContent, true);

    $keys = array_unique(array_merge(array_keys($firstFixtures), array_keys($secondFixtures)));

    $result = array_reduce($keys, function ($acc, $key) use ($firstFixtures, $secondFixtures){
        if (array_key_exists($key, $firstFixtures) && gettype($firstFixtures[$key]) === "boolean") {
            $firstFixtures[$key] = ($firstFixtures[$key] === true) ? "true" : "false";
        }
        if (array_key_exists($key, $secondFixtures) && gettype($secondFixtures[$key]) === "boolean") {
            $secondFixtures[$key] = ($secondFixtures[$key] === true) ? "true" : "false";
        }
        if (array_key_exists($key, $firstFixtures) && array_key_exists($key, $secondFixtures)) {
            if ($firstFixtures[$key] === $secondFixtures[$key]) {
                $acc = "{$acc}    {$key}: {$firstFixtures[$key]}\n";
            } else {
                $acc = "{$acc} -  {$key}: {$firstFixtures[$key]}\n";
                $acc = "{$acc} +  {$key}: {$secondFixtures[$key]}\n";
            }
        } elseif (array_key_exists($key, $firstFixtures)) {
            $acc = "{$acc} -  {$key}: {$firstFixtures[$key]}\n";
        } else {
            $acc = "{$acc} +  {$key}: {$secondFixtures[$key]}\n";
        }
        return $acc;
    }, "{\n");
    $ResultWithEnd = "{$result}}\n";
    print_r($ResultWithEnd);
    return $ResultWithEnd;
}