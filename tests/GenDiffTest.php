<?php

namespace Hexlet\Code\Tests;

use PHPUnit\Framework\TestCase;

use function Hexlet\Code\Differ\gendiff;

class GenDiffTest extends TestCase
{
    /** @dataProvider diffTwoFileProvider */
    public function testGendiffTwofile($file1, $file2, $format, $expected): void
    {
        $result = gendiff($file1, $file2, $format);
        $expected = file_get_contents(__DIR__ . $expected);
        $this->assertEquals($expected, $result);
    }

    public function diffTwoFileProvider() {
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
            ]
        ];
    }
}
