<?php

namespace Hexlet\Code\BuildAst;

use function Hexlet\Code\Formaters\Stylish\type;

/**
 * @param array<mixed> $firstFixtures
 * @param array<mixed> $secondFixtures
 * @return array<mixed>
 */
function buildAst(array $firstFixtures, array $secondFixtures): array
{
    $keys = array_merge(array_keys($firstFixtures), array_keys($secondFixtures));
    $keys = array_unique($keys);
    asort($keys);
    $result = array_map(fn($key) => mappedAst($key, $firstFixtures, $secondFixtures), $keys);
    //print_r($result);
    return $result;
}

/**
 * @param string $type
 * @param string $key
 * @param mixed $value
 * @param mixed $secondValue
 * @return array<mixed>
 */
function astNode($type, $key, $value, $secondValue = null): array
{
    return ['type' => $type,
        'key' => $key,
        'value' => $value,
        'secondValue' => $secondValue];
}

/**
 * @param string $key
 * @param array<mixed> $firstFixtures
 * @param array<mixed> $secondFixtures
 * @return array<mixed>
 */
function mappedAst($key, $firstFixtures, $secondFixtures)
{
    //var_dump($firstFixtures);
    //print_r($secondFixtures);
    $firstContent = $firstFixtures[$key] ?? null;
    $secondContent = $secondFixtures[$key] ?? null;
    if (is_array($firstContent) && is_array($secondContent)) {
        return astNode('hasChildren', $key, buildAst($firstContent, $secondContent));
    }
    if (!array_key_exists($key, $firstFixtures)) {
        return astNode('added', $key, normalizedContent($secondContent));
    }
    if (!array_key_exists($key, $secondFixtures)) {
        return  astNode('deleted', $key, normalizedContent($firstContent));
    }
    if ($firstContent !== $secondContent) {
        return astNode('changed', $key, normalizedContent($firstContent), normalizedContent($secondContent));
    }
    return astNode('unchanged', $key, $firstContent);
}

/**
 * @param array<mixed> $content
 * @return mixed
 */
function normalizedContent($content)
{
    if (!is_array($content)) {
        return $content;
    }
    $result = [];
    foreach ($content as $key => $value) {
        if (is_array($value)) {
            $value = normalizedContent($value);
        }
        $result[] = ['type' => 'unchanged', 'key' => $key, 'value' => $value];
    }
    return $result;
}
