<?php

/**
 * Parses and verifies the doc comments for files.
 *
 * PHP version 5
 *
 * @category  Parser
 * @package   Cli_Parser
 * @author    Aslan Autlev <autlevaslan@gmail.com>
 * @copyright 2021-2022 Home
 * @license   github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

 namespace Hexlet\Code\Differ;

use Exception;
use Symfony\Component\Yaml\Yaml;

use function Hexlet\Code\Parsers\Parsers\parse;

/**
 * Function gendiff() find diff for two files
 *
 * @param string $firstFile  first file for difference
 * @param string $secondFile second file for difference
 * @param string $format     diff two file
 *
 * @return string
 */
function genDiff(string $firstFile, string $secondFile, string $format = 'stylish')
{
    $firstFixtures = preparationOfFile($firstFile);
    $secondFixtures = preparationOfFile($secondFile);
    $result = diffFixtures($firstFixtures, $secondFixtures);
    print_r($result);
    return $result;
}

/**
 * Function preparationOfFile() file to array
 *
 * @param string $file file to array
 *
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
 * Function fullPathToFile() doing absolute path
 *
 * @param string $file file to array
 *
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
 * Function yamlDecode() parse Yaml file
 *
 * @param string $fileContent parse Yaml file
 *
 * @return array<mixed>
 */
function yamlDecode(string $fileContent): array
{
    return Yaml::parse($fileContent);
}

/**
 * Function jsonDecode() parse json file
 *
 * @param string $fileContent parse json file
 *
 * @return array<mixed>
 */
function jsonDecode(string $fileContent): array
{
    return json_decode($fileContent, true);
}

/**
 * Function normalizeBoolean() boolean => "boolean"
 *
 * @param array<mixed> $fixtures boolean => "boolean"
 *
 * @return array<mixed>
 */
function normalizeBoolean(array $fixtures): array
{
    foreach ($fixtures as $key => $item) {
        if (is_bool($item)) {
            $fixtures[$key] = ($fixtures[$key] === true) ? "true" : "false";
        }
    }
    return $fixtures;
}

/**
 * Function diffFixtures() find diff for two array
 *
 * @param array<mixed> $firstFixtures  content of first file
 * @param array<mixed> $secondFixtures content of second file
 *
 * @return string  return string diff of two file
 */
function diffFixtures(array $firstFixtures, array $secondFixtures): string
{
    print_r([$firstFixtures]);
    print_r([$secondFixtures]);
    $keys = array_merge(array_keys($firstFixtures), array_keys($secondFixtures));
    $keys = array_unique($keys);
    asort($keys);
    $result = array_reduce(
        $keys,
        function (string $acc, $key) use ($firstFixtures, $secondFixtures) {
            if (
                array_key_exists($key, $firstFixtures)
                && array_key_exists($key, $secondFixtures)
            ) {
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
        },
        "{\n"
    );
    return "{$result}}\n";
}
