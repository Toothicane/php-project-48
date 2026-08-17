<?php

namespace Hexlet\Code;

use function Funct\Collection\sortBy;

function genDiff(
    string $pathToFile1,
    string $pathToFile2,
    string $formatName = 'stylish'
): string {
    $firstData = parse($pathToFile1);
    $secondData = parse($pathToFile2);

    $diff = buildDiff($firstData, $secondData);
    $formatter = getFormatter($formatName);
    return $formatter($diff);
}

function buildDiff(
    array $firstData,
    array $secondData,
): array {
    $keys = array_unique(array_merge(array_keys($firstData), array_keys($secondData)));
    $sortedKeys = array_values(sortBy($keys, fn($key) => $key));
    $diff = array_map(function ($key) use ($firstData, $secondData) {
        if (!array_key_exists($key, $firstData)) {
            return  [
                'key' => $key,
                'type' => 'added',
                'value' => $secondData[$key]
            ];
        }

        if (!array_key_exists($key, $secondData)) {
            return [
                'key' => $key,
                'type' => 'removed',
                'value' => $firstData[$key]
            ];
        }

        if (isAssocArray($firstData[$key]) && isAssocArray($secondData[$key])) {
            return [
                'key' => $key,
                'type' => 'nested',
                'children' => buildDiff($firstData[$key], $secondData[$key])
            ];
        }

        if ($firstData[$key] === $secondData[$key]) {
            return [
                'key' => $key,
                'type' => 'unchanged',
                'value' => $firstData[$key]
            ];
        }

        return [
            'key' => $key,
            'type' => 'changed',
            'oldValue' => $firstData[$key],
            'newValue' => $secondData[$key]
        ];
    }, $sortedKeys);

    return $diff;
}

function isAssocArray(mixed $value): bool
{
    if (!is_array($value)) {
        return false;
    }
    if ($value === []) {
        return false;
    }

    return array_keys($value) !== range(0, count($value) - 1);
}
