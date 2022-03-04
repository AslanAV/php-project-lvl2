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
        $result = gendiff($file1, $file2, $format);
        $expected = file_get_contents(__DIR__ . $expected);
        $this->assertEquals($expected, $result);
    }

    /**
     * @return array<int, array<int, string>>
     */
    public function diffTwoFileProvider()
    {
        return [
            [
                "/tests/fixtures/filepath1.json",
                "/tests/fixtures/filepath2.json",
                "stylish",
                "/fixtures/expectedTwoFileFormatStylish.txt"
            ],
            [
                "/tests/fixtures/fileRecursive1.yaml",
                "/tests/fixtures/fileRecursive2.yaml",
                "stylish",
                "/fixtures/expectedTwoFileFormatStylish.txt"
            ],
            [
                "/tests/fixtures/filepath1.json",
                "/tests/fixtures/filepath2.json",
                "plain",
                "/fixtures/expectedTwoFileFormatPlain.txt"
            ],
            [
                "/tests/fixtures/fileRecursive1.yaml",
                "/tests/fixtures/fileRecursive2.yaml",
                "plain",
                "/fixtures/expectedTwoFileFormatPlain.txt"
            ],
            [
                "/tests/fixtures/filepath1.json",
                "/tests/fixtures/filepath2.json",
                "json",
                "/fixtures/expectedTwoFileFormatJson.txt"
            ],
            [
                "/tests/fixtures/fileRecursive1.yaml",
                "/tests/fixtures/fileRecursive2.yaml",
                "json",
                "/fixtures/expectedTwoFileFormatJson.txt"
            ]
        ];
    }
}
