<?php

namespace Hexlet\Code\Tests;

use PHPUnit\Framework\TestCase;

use function Hexlet\Code\Differ\gendiff;

class GenDiffTest extends TestCase
{
    public function testDiffTwoJSON(): void
    {
        $firstFile1 = "/tests/fixtures/file1.json";
        $secondFile2 = "/tests/fixtures/file2.json";
        $resultTwoJSON = gendiff($firstFile1, $secondFile2);
        $expectedTwoJSON = file_get_contents(__DIR__ . "/fixtures/expectedTwoJSON.txt");
        $this->assertEquals($expectedTwoJSON, $resultTwoJSON);
    }

    public function testDiffTwoYAML(): void
    {
        $firstFile1 = "/tests/fixtures/filepath1.yml";
        $secondFile2 = "/tests/fixtures/filepath2.yml";
        $resultTwoYML = gendiff($firstFile1, $secondFile2);
        $expectedTwoYML = file_get_contents(__DIR__ . "/fixtures/expectedTwoYML.txt");
        $this->assertEquals($expectedTwoYML, $resultTwoYML);
    }

    public function testDiffTwoJSONRecursive(): void
    {
        $firstFile1 = "/tests/fixtures/filepath1.json";
        $secondFile2 = "/tests/fixtures/filepath2.json";
        $resultTwoJSON = gendiff($firstFile1, $secondFile2);
        $expectedTwoJSON = file_get_contents(__DIR__ . "/fixtures/expectedTwoJSONRecursive.txt");
        $this->assertEquals($expectedTwoJSON, $resultTwoJSON);
    }

    public function testDiffTwoYAMLRecursive(): void
    {
        $firstFile1 = "/tests/fixtures/fileRecursive1.yaml";
        $secondFile2 = "/tests/fixtures/fileRecursive2.yaml";
        $resultTwoJSON = gendiff($firstFile1, $secondFile2);
        $expectedTwoJSON = file_get_contents(__DIR__ . "/fixtures/expectedTwoYAMLRecursive.txt");
        $this->assertEquals($expectedTwoJSON, $resultTwoJSON);
    }
}
