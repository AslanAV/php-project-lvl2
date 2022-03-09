<?php

namespace Hexlet\Code\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\gendiff;

class GenDiffTest extends TestCase
{
    /**
     * @dataProvider diffTwoFileProvider
     *
     * @param string $file1
     * @param string $file2
     * @param string $format
     * @param string $expected
     * @return void
     */
    public function testGendiffTwofile($file1, $file2, $format, $expected)
    {
        $this->assertStringEqualsFile($expected, gendiff($file1, $file2, $format));
    }

    /**
     * @return array<int, array<int, string>>
     */
    public function diffTwoFileProvider()
    {
        return [
            [
                "tests/fixtures/filepath1.json",
                "tests/fixtures/filepath2.json",
                "stylish",
                __DIR__ . "/fixtures/expectedTwoFileFormatStylish.txt"
            ],
            [
                "tests/fixtures/fileRecursive1.yaml",
                "tests/fixtures/fileRecursive2.yaml",
                "stylish",
                __DIR__ . "/fixtures/expectedTwoFileFormatStylish.txt"
            ],
            [
                "tests/fixtures/filepath1.json",
                "tests/fixtures/filepath2.json",
                "plain",
                __DIR__ . "/fixtures/expectedTwoFileFormatPlain.txt"
            ],
            [
                "tests/fixtures/fileRecursive1.yaml",
                "tests/fixtures/fileRecursive2.yaml",
                "plain",
                __DIR__ . "/fixtures/expectedTwoFileFormatPlain.txt"
            ],
            [
                "tests/fixtures/filepath1.json",
                "tests/fixtures/filepath2.json",
                "json",
                __DIR__ . "/fixtures/expectedTwoFileFormatJson.txt"
            ],
            [
                "tests/fixtures/fileRecursive1.yaml",
                "tests/fixtures/fileRecursive2.yaml",
                "json",
                __DIR__ . "/fixtures/expectedTwoFileFormatJson.txt"
            ]
        ];
    }
}
