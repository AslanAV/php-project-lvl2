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
                $this->getFullPathToFile("filepath1.json"),
                $this->getFullPathToFile("filepath2.json"),
                "stylish",
                $this->getFullPathToFile("expectedTwoFileFormatStylish.txt")
            ],
            [
                $this->getFullPathToFile("fileRecursive1.yaml"),
                $this->getFullPathToFile("fileRecursive2.yaml"),
                "stylish",
                $this->getFullPathToFile("expectedTwoFileFormatStylish.txt")
            ],
            [
                $this->getFullPathToFile("filepath1.json"),
                $this->getFullPathToFile("filepath2.json"),
                "plain",
                $this->getFullPathToFile("expectedTwoFileFormatPlain.txt")
            ],
            [
                $this->getFullPathToFile("fileRecursive1.yaml"),
                $this->getFullPathToFile("fileRecursive2.yaml"),
                "plain",
                $this->getFullPathToFile("expectedTwoFileFormatPlain.txt")
            ],
            [
                $this->getFullPathToFile("filepath1.json"),
                $this->getFullPathToFile("filepath2.json"),
                "json",
                $this->getFullPathToFile("expectedTwoFileFormatJson.txt")
            ],
            [
                $this->getFullPathToFile("fileRecursive1.yaml"),
                $this->getFullPathToFile("fileRecursive2.yaml"),
                "json",
                $this->getFullPathToFile("expectedTwoFileFormatJson.txt")
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
