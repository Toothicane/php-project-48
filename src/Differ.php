<?php

namespace Hexlet\Code;

use RuntimeException;

use function Funct\Collection\sortBy;

function parse(string $content): array
{
    $decoded = json_decode($content, true, flags: JSON_THROW_ON_ERROR);
    return $decoded;
}

function valueToString(mixed $value): string
{
    if (is_bool($value)) {
        return $value ? "true" : "false";
    }

    return (string) $value;
}

function genDiff(string $pathToFile1, string $pathToFile2): string
{
    $firstContent = file_get_contents($pathToFile1);
    if ($firstContent === false) {
        throw new RuntimeException("Couldn't read {$pathToFile1}");
    }
    $secondContent = file_get_contents($pathToFile2);
    if ($secondContent === false) {
        throw new RuntimeException("Couldn't read {$pathToFile2}");
    }

    $firstData = parse($firstContent);
    $secondData = parse($secondContent);

    $keys = array_unique(array_merge(array_keys($firstData), array_keys($secondData)));

    $sortedKeys = array_values(sortBy($keys, fn($key) => $key));

    $diffLines = array_map(function ($key) use ($firstData, $secondData) {
        if (array_key_exists($key, $secondData)) {
            if (array_key_exists($key, $firstData)) {
                if ($firstData[$key] === $secondData[$key]) {
                    $value = valueToString($secondData[$key]);
                    return "  {$key}: {$value}";
                } else {
                    $value1 = valueToString($firstData[$key]);
                    $value2 = valueToString($secondData[$key]);
                    return "- {$key}: {$value1}\n+ {$key}: {$value2}";
                }
            } else {
                $value = valueToString($secondData[$key]);
                return "+ {$key}: {$value}";
            }
        } else {
            $value = valueToString($firstData[$key]);
            return "- {$key}: {$value}";
        }
    }, $sortedKeys);

    $diff = implode("\n", $diffLines);
    return $diff;
}
