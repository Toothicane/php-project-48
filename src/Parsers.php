<?php

namespace Hexlet\Code;

use Symfony\Component\Yaml\Yaml;
use RuntimeException;

function parse(string $filePath): array
{
    $content = file_get_contents($filePath);
    if ($content === false) {
        throw new RuntimeException("Couldn't read {$filePath}");
    }

    $extension = pathinfo($filePath, PATHINFO_EXTENSION);
    $parser = getParser($extension);

    return $parser($content);
}

function getParser(string $extension): callable
{
    return match ($extension) {
        'json' => parseJson(...),
        'yml', 'yaml' => parseYml(...),
        default => throw new RuntimeException(
            "Unsupported file format: {$extension}"
        ),
    };
}

function parseJson(string $content): array
{
    return json_decode($content, true, flags: JSON_THROW_ON_ERROR);
}

function parseYml(string $content): array
{
    return Yaml::parse($content);
}
