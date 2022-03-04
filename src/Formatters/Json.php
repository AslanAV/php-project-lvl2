<?php

namespace Hexlet\Code\Formatters\Json;

use Exception;

/**
 * @param array<mixed> $ast
 * @return mixed
 */
function formatedToJson($ast)
{
    return json_encode($ast);
}
