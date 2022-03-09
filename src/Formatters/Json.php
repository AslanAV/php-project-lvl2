<?php

namespace Differ\Formatters\Json;

/**
 * @param array<mixed> $ast
 * @return mixed
 */
function formatedToJson(array $ast)
{
    return json_encode($ast);
}
