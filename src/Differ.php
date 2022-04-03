<?php

namespace Differ\Differ;

use Exception;

use function Differ\Parsers\parse;
use function Differ\BuildAst\buildAst;
use function Differ\Formatters\format;

/**
 * @param string $firstFile
 * @param string $secondFile
 * @param string $format
 * @return string
 */
function genDiff(string $firstFile, string $secondFile, string $format = 'stylish'): string
{
    $firstContent = getContentFromFileAndParse($firstFile);
    $secondContent = getContentFromFileAndParse($secondFile);
    $ast = buildAst($firstContent, $secondContent);

    return format($ast, $format);
}

/**
 * @param string $file
 * @return array<string>
 */
function getContentFromFileAndParse(string $file): array
{
    $fileWithFullPath = getFullPathToFile($file);
    $fileContent = file_get_contents($fileWithFullPath);
    if ($fileContent === false) {
        throw new Exception("Can't read file");
    }

    $fileType = pathinfo($fileWithFullPath, PATHINFO_EXTENSION);

    return parse($fileType, $fileContent);
}

/**
 * @param string $file
 * @return string
 */
function getFullPathToFile(string $file): string
{
    if (strpos($file, '/') === 0) {
        return $file;
    }

    return __DIR__ . '/../' . $file;
}
