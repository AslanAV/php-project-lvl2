<?php

namespace Differ\Parsers\Parsers;

use Exception;
use Symfony\Component\Yaml\Yaml;

/**
 * @param string $fileWithFullPath
 * @param string $fileContent
 * @return array<mixed>
 */
function parse($fileWithFullPath, $fileContent)
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

/**
 * @param string $fileContent
 * @return array<mixed>
 */
function yamlDecode($fileContent)
{
    return Yaml::parse($fileContent);
}

/**
 * @param string $fileContent
 * @return array<mixed>
 */
function jsonDecode($fileContent)
{
    return json_decode($fileContent, true);
}
