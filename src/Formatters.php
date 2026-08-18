<?php

namespace Hexlet\Code;

use RuntimeException;

use function Hexlet\Code\Formatters\formatPlain;
use function Hexlet\Code\Formatters\formatStylish;
use function Hexlet\Code\Formatters\formatJson;

function getFormatter(string $format): callable
{
    return match ($format) {
        'stylish' => formatStylish(...),
        'plain' => formatPlain(...),
        'json' => formatJson(...),
        default => throw new RuntimeException(
            "Unknown format: {$format}"
        ),
    };
}
