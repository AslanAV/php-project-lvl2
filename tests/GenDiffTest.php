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
        $files = [
            [
                "filepath1.json",
                "filepath2.json"
            ],
            [
                "fileRecursive1.yaml",
                "fileRecursive2.yaml"
            ]
        ];
        $formatters = [
            ["stylish", "expectedTwoFileFormatStylish.txt"],
            ["plain", "expectedTwoFileFormatPlain.txt"],
            ["json", "expectedTwoFileFormatJson.txt"]
        ];
        $result = [];
        foreach ($files as [$file1, $file2]) {
            foreach ($formatters as [$formatter, $expected]) {
                $result[] = [
                    $this->getFullPathToFile($file1),
                    $this->getFullPathToFile($file2),
                    $formatter,
                    $this->getFullPathToFile($expected)
                ];
            }
        }
        return $result;
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
