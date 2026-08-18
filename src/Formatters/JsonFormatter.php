<?php

namespace Hexlet\Code\Formatters;

function formatJson(array $diff): string
{
    return json_encode($diff, JSON_THROW_ON_ERROR);
}
