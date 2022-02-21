<?php

namespace Hexlet\Code\Tests;

use PHPUnit\Framework\TestCase;

use function Hexlet\Code\Differ\gendiff;

class GenDiffTest extends TestCase
{
    public function testDiff(): void
    {
        $firstFile1 = "/tests/fixtures/file1.json";
        $secondFile2 = "/tests/fixtures/file2.json";
        $resultTwoJSON = gendiff($firstFile1, $secondFile2);
        $expectedTwoJSON = file_get_contents(__DIR__ . "/fixtures/expectedTwoJSON.txt");
        $this->assertEquals($expectedTwoJSON, $resultTwoJSON);
    }
}
