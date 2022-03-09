<?php

namespace Differ\Parsers\Parsers;

use Exception;
use Symfony\Component\Yaml\Yaml;

/**
 * @param string $fileType
 * @param string $fileContent
 * @return array<mixed>
 */
function parse(string $fileType, string $fileContent): array
{
    switch ($fileType) {
        case "yml":
        case "yaml":
            $content = yamlDecode($fileContent);
            break;
        case "json":
            $content = jsonDecode($fileContent);
            break;
        default:
            throw new Exception('Unknown type of file ' . $fileType);
    }
    return $content;
}

/**
 * @param string $fileContent
 * @return array<mixed>
 */
function yamlDecode(string $fileContent): array
{
    return Yaml::parse($fileContent);
}

/**
 * @param string $fileContent
 * @return array<mixed>
 */
function jsonDecode(string $fileContent): array
{
    return json_decode($fileContent, true);
}
