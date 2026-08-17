<?php

namespace Hexlet\Code\Formatters;

function formatPlain(array $diff): string
{
    return formatInnerPlain($diff);
}

function formatInnerPlain(array $diff, string $parentPath = ''): string
{
    $lines = array_map(function ($node) use ($parentPath) {
        $key = $node['key'];

        $path = $parentPath === ''
            ? $key
            : "{$parentPath}.{$key}";

        return match ($node['type']) {
            'unchanged' => '',

            'nested' =>
                formatInnerPlain($node['children'], $path),

            'added' =>
                "Property '{$path}' was added with value: "
                . valueToPlain($node['value']),

            'removed' =>
                "Property '{$path}' was removed",

            'changed' =>
                "Property '{$path}' was updated. From "
                . valueToPlain($node['oldValue'])
                . " to "
                . valueToPlain($node['newValue']),
        };
    }, $diff);

    return implode(
        "\n",
        array_filter($lines, fn($line) => $line !== '')
    );
}

function valueToPlain(mixed $value): string
{
    if (is_array($value)) {
        return '[complex value]';
    }

    if (is_string($value)) {
        return "'{$value}'";
    }

    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if ($value === null) {
        return 'null';
    }

    return (string) $value;
}
