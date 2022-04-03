<?php

namespace Differ\BuildAst;

use function Functional\map;
use function Functional\sort;

/**
 * @param array<mixed> $firstContentFromFile
 * @param array<mixed> $secondContentFromFile
 * @return array<mixed>
 */
function buildAst(array $firstContentFromFile, array $secondContentFromFile): array
{
    $keys = array_merge(array_keys($firstContentFromFile), array_keys($secondContentFromFile));
    $uniqueKeys = array_unique($keys);
    $sortedKeys = sort($uniqueKeys, fn ($a, $b) => strcmp($a, $b), false);

    return array_map(fn($key) => getMapAst($key, $firstContentFromFile, $secondContentFromFile), $sortedKeys);
}

/**
 * @param string $type
 * @param string $key
 * @param mixed $value
 * @param mixed $secondValue
 * @return array<mixed>
 */
function getAstNode(string $type, string $key, $value, $secondValue = null): array
{
    return ['type' => $type,
        'key' => $key,
        'value' => $value,
        'secondValue' => $secondValue];
}

/**
 * @param string $key
 * @param array<mixed> $firstContentFromFile
 * @param array<mixed> $secondContentFromFile
 * @return array<mixed>
 */
function getMapAst(string $key, array $firstContentFromFile, array $secondContentFromFile): array
{
    $firstContent = $firstContentFromFile[$key] ?? null;
    $secondContent = $secondContentFromFile[$key] ?? null;
    if (is_array($firstContent) && is_array($secondContent)) {
        return getAstNode('hasChildren', $key, buildAst($firstContent, $secondContent));
    }

    if (!array_key_exists($key, $firstContentFromFile)) {
        return getAstNode('added', $key, normalizeContent($secondContent));
    }

    if (!array_key_exists($key, $secondContentFromFile)) {
        return  getAstNode('deleted', $key, normalizeContent($firstContent));
    }

    if ($firstContent !== $secondContent) {
        return getAstNode('changed', $key, normalizeContent($firstContent), normalizeContent($secondContent));
    }

    return getAstNode('unchanged', $key, $firstContent);
}

/**
 * @param mixed $content
 * @return mixed
 */
function normalizeContent(mixed $content)
{
    $iter = function ($content) use (&$iter) {
        if (!is_array($content)) {
            return $content;
        }

        $keys = array_keys($content);
        return map($keys, function ($key) use ($content, $iter) {
            $value = (is_array($content[$key])) ? $iter($content[$key]) : $content[$key];

            return ['type' => 'unchanged', 'key' => $key, 'value' => $value];
        });
    };

    return $iter($content);
}

/**
 * @param array<mixed> $node
 * @return string
 */
function getType(array $node): string
{
    return $node['type'];
}

/**
 * @param array<mixed> $node
 * @return string
 */
function getKey(array $node): string
{
    return $node['key'];
}

/**
 * @param array<mixed> $node
 * @return mixed
 */
function getValue(array $node)
{
    return $node['value'];
}

/**
 * @param array<mixed> $node
 * @return mixed
 */
function getSecondValue(array $node)
{
    return $node['secondValue'];
}

/**
 * @param array<mixed> $node
 * @return array<mixed>
 */
function getChildren(array $node): array
{
    return $node['value'];
}

/**
 * @param array<mixed> $node
 * @return bool
 */
function hasChildren(array $node): bool
{
    return array_key_exists('children', $node);
}
