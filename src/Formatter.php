<?php

namespace Hexlet\Code;

use function Funct\Collection\sortBy;

function formatStylish(array $diff): string
{
    return "{\n" . formatInnerStylish($diff, 1) . "\n}";
}

function formatInnerStylish(array $diff, int $depth): string
{
    $lines = array_map(function ($node) use ($depth) {
        $key = $node['key'];

        $indent = str_repeat(' ', $depth * 4 - 2);
        $nestedIndent = str_repeat(' ', $depth * 4);

        return match ($node['type']) {
            'unchanged' =>
                "{$indent}  {$key}: "
                . valueToString($node['value'], $depth),

            'added' =>
                "{$indent}+ {$key}: "
                . valueToString($node['value'], $depth),

            'removed' =>
                "{$indent}- {$key}: "
                . valueToString($node['value'], $depth),

            'changed' =>
                "{$indent}- {$key}: "
                . valueToString($node['oldValue'], $depth)
                . "\n"
                . "{$indent}+ {$key}: "
                . valueToString($node['newValue'], $depth),

            'nested' =>
                "{$indent}  {$key}: {\n"
                . formatInnerStylish($node['children'], $depth + 1)
                . "\n"
                . "{$nestedIndent}}",
        };
    }, $diff);

    return implode("\n", $lines);
}

function valueToString(mixed $value, int $depth): string
{
    if (!is_array($value)) {
        return scalarValueToString($value);
    }

    $indent = str_repeat(' ', $depth * 4);
    $childIndent = str_repeat(' ', ($depth + 1) * 4);

    $keys = array_keys($value);
    $sortedKeys = array_values(sortBy($keys, fn($key) => $key));

    $lines = array_map(
        function ($key) use ($value, $childIndent, $depth) {
            $item = $value[$key];

            if (is_array($item)) {
                $itemValue = valueToString($item, $depth + 1);
            } else {
                $itemValue = scalarValueToString($item);
            }

            return "{$childIndent}{$key}: {$itemValue}";
        },
        $sortedKeys
    );

    return "{\n"
        . implode("\n", $lines)
        . "\n{$indent}}";
}

function scalarValueToString(mixed $value): string
{
    if (is_bool($value)) {
        return $value ? "true" : "false";
    }

    if ($value === null) {
        return "null";
    }

    return (string) $value;
}
