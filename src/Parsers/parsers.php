<?php

namespace Hexlet\Code\Parsers\Parsers;

use Exception;

use function Hexlet\Code\Differ\jsonDecode;
use function Hexlet\Code\Differ\yamlDecode;

/**
 * @param string $fileWithFullPath
 * @param string $fileContent
 * @return array<mixed>
 */
function parse(string $fileWithFullPath, string $fileContent): array
{
    $fileType = pathinfo($fileWithFullPath, PATHINFO_EXTENSION);
    switch ($fileType) {
        case "yml":
        case "yaml":
            $fixture = yamlDecode($fileContent);
            break;
        case "json":
            $fixture = jsonDecode($fileContent);
            break;
        default:
            throw new Exception('Unknown type of file ' . $fileType);
    }
    return $fixture;
}
