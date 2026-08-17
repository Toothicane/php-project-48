<?php

namespace Hexlet\Code;

use RuntimeException;

use function Hexlet\Code\Formatters\formatPlain;
use function Hexlet\Code\Formatters\formatStylish;

function getFormatter(string $format): callable
{
    return match ($format) {
        'stylish' => formatStylish(...),
        'plain' => formatPlain(...),
        default => throw new RuntimeException(
            "Unknown format: {$format}"
        ),
    };
}
