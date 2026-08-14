<?php

namespace Hexlet\Code;

use function Funct\Collection\sortBy;
use function Hexlet\Code\parse;

function valueToString(mixed $value): string
{
    if (is_bool($value)) {
        return $value ? "true" : "false";
    }

    return (string) $value;
}

function genDiff(string $pathToFile1, string $pathToFile2): string
{
    $firstData = parse($pathToFile1);
    $secondData = parse($pathToFile2);

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
