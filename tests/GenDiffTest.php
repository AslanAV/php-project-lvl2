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
        $fixture1 = $this->getFullPathToFile($file1);
        $fixture2 = $this->getFullPathToFile($file2);
        $expectedDiff = $this->getFullPathToFile($expected);
        $this->assertStringEqualsFile($expectedDiff, gendiff($fixture1, $fixture2, $format));
    }

    /**
     * @return array<int, array<int, string>>
     */
    public function diffTwoFileProvider()
    {
        return [
            [
                "filepath1.json",
                "filepath2.json",
                "stylish",
                "expectedTwoFileFormatStylish.txt"
            ],
            [
                "fileRecursive1.yaml",
                "fileRecursive2.yaml",
                "stylish",
                "expectedTwoFileFormatStylish.txt"
            ],
            [
                "filepath1.json",
                "filepath2.json",
                "plain",
                "expectedTwoFileFormatPlain.txt"
            ],
            [
                "fileRecursive1.yaml",
                "fileRecursive2.yaml",
                "plain",
                "expectedTwoFileFormatPlain.txt"
            ],
            [
                "filepath1.json",
                "filepath2.json",
                "json",
                "expectedTwoFileFormatJson.txt"
            ],
            [
                "fileRecursive1.yaml",
                "fileRecursive2.yaml",
                "json",
                "expectedTwoFileFormatJson.txt"
            ]
        ];
    }

    /**
     * @param string $path
     * @return string
     */
    private function getFullPathToFile(string $path): string
    {
        return __DIR__ . "/fixtures/" . $path;
    }
}
