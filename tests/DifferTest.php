<?php

namespace Hexlet\Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

use function Hexlet\Code\genDiff;
use function Hexlet\Code\buildDiff;
use function Hexlet\Code\parse;

class DifferTest extends TestCase
{
    public static function fileFormatProvider(): array
    {
        return [
            ['json'],
            ['yml'],
        ];
    }

    #[DataProvider('fileFormatProvider')]
    public function testEmptyFiles(string $extension): void
    {
        $file = __DIR__ . "/fixtures/{$extension}/empty.{$extension}";
        $data = parse($file);
        $diff = buildDiff($data, $data);

        $this->assertSame([], $diff);
    }

    #[DataProvider('fileFormatProvider')]
    public function testTypes(string $extension): void
    {
        $file1 = __DIR__ . "/fixtures/{$extension}/to_string_first.{$extension}";
        $file2 = __DIR__ . "/fixtures/{$extension}/to_string_second.{$extension}";
        $firstData = parse($file1);
        $secondData = parse($file2);

        $diff = buildDiff($firstData, $secondData);

        $expected = [
            [
                'key' => 'enabled',
                'type' => 'changed',
                'oldValue' => false,
                'newValue' => true,
            ],
            [
                'key' => 'retries',
                'type' => 'changed',
                'oldValue' => 3,
                'newValue' => '3',
            ],
        ];

        $this->assertSame($expected, $diff);
    }

    #[DataProvider('fileFormatProvider')]
    public function testNestedToEmpty(string $extension): void
    {
        $file1 = __DIR__ . "/fixtures/{$extension}/nested_first.{$extension}";
        $file2 = __DIR__ . "/fixtures/{$extension}/empty.{$extension}";
        $firstData = parse($file1);
        $secondData = parse($file2);

        $diff = buildDiff($firstData, $secondData);

        $expected = [
            [
                'key' => 'common',
                'type' => 'removed',
                'value' => [
                    'setting1' => 'Value 1',
                    'setting2' => 200,
                    'setting3' => true,
                    'setting6' => [
                        'key' => 'value',
                        'doge' => [
                            'wow' => '',
                        ],
                    ],
                ],
            ],
            [
                'key' => 'group1',
                'type' => 'removed',
                'value' => [
                    'baz' => 'bas',
                    'foo' => 'bar',
                    'nest' => [
                        'key' => 'value',
                    ],
                ],
            ],
            [
                'key' => 'group2',
                'type' => 'removed',
                'value' => [
                    'abc' => 12345,
                    'deep' => [
                        'id' => 45,
                    ],
                ],
            ],
        ];

        $this->assertSame($expected, $diff);
    }

    #[DataProvider('fileFormatProvider')]
    public function testEmptyToNested(string $extension): void
    {
        $file1 = __DIR__ . "/fixtures/{$extension}/empty.{$extension}";
        $file2 = __DIR__ . "/fixtures/{$extension}/nested_second.{$extension}";
        $firstData = parse($file1);
        $secondData = parse($file2);

        $diff = buildDiff($firstData, $secondData);

        $expected = [
            [
                'key' => 'common',
                'type' => 'added',
                'value' => [
                    'follow' => false,
                    'setting1' => 'Value 1',
                    'setting3' => null,
                    'setting4' => 'blah blah',
                    'setting5' => [
                        'key5' => 'value5',
                    ],
                    'setting6' => [
                        'key' => 'value',
                        'ops' => 'vops',
                        'doge' => [
                            'wow' => 'so much',
                        ],
                    ],
                ],
            ],
            [
                'key' => 'group1',
                'type' => 'added',
                'value' => [
                    'foo' => 'bar',
                    'baz' => 'bars',
                    'nest' => 'str',
                ],
            ],
            [
                'key' => 'group3',
                'type' => 'added',
                'value' => [
                    'deep' => [
                        'id' => [
                            'number' => 45,
                        ],
                    ],
                    'fee' => 100500,
                ],
            ],
        ];
        $this->assertSame($expected, $diff);
    }

    #[DataProvider('fileFormatProvider')]
    public function testNested(string $extension): void
    {
        $file1 = __DIR__ . "/fixtures/{$extension}/nested_first.{$extension}";
        $file2 = __DIR__ . "/fixtures/{$extension}/nested_second.{$extension}";
        $firstData = parse($file1);
        $secondData = parse($file2);

        $diff = buildDiff($firstData, $secondData);

        $expected = [
            [
                'key' => 'common',
                'type' => 'nested',
                'children' => [
                    [
                        'key' => 'follow',
                        'type' => 'added',
                        'value' => false,
                    ],
                    [
                        'key' => 'setting1',
                        'type' => 'unchanged',
                        'value' => 'Value 1',
                    ],
                    [
                        'key' => 'setting2',
                        'type' => 'removed',
                        'value' => 200,
                    ],
                    [
                        'key' => 'setting3',
                        'type' => 'changed',
                        'oldValue' => true,
                        'newValue' => null,
                    ],
                    [
                        'key' => 'setting4',
                        'type' => 'added',
                        'value' => 'blah blah',
                    ],
                    [
                        'key' => 'setting5',
                        'type' => 'added',
                        'value' => [
                            'key5' => 'value5',
                        ],
                    ],
                    [
                        'key' => 'setting6',
                        'type' => 'nested',
                        'children' => [
                            [
                                'key' => 'doge',
                                'type' => 'nested',
                                'children' => [
                                    [
                                        'key' => 'wow',
                                        'type' => 'changed',
                                        'oldValue' => '',
                                        'newValue' => 'so much',
                                    ],
                                ],
                            ],
                            [
                                'key' => 'key',
                                'type' => 'unchanged',
                                'value' => 'value',
                            ],
                            [
                                'key' => 'ops',
                                'type' => 'added',
                                'value' => 'vops',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'key' => 'group1',
                'type' => 'nested',
                'children' => [
                    [
                        'key' => 'baz',
                        'type' => 'changed',
                        'oldValue' => 'bas',
                        'newValue' => 'bars',
                    ],
                    [
                        'key' => 'foo',
                        'type' => 'unchanged',
                        'value' => 'bar',
                    ],
                    [
                        'key' => 'nest',
                        'type' => 'changed',
                        'oldValue' => [
                            'key' => 'value',
                        ],
                        'newValue' => 'str',
                    ],
                ],
            ],
            [
                'key' => 'group2',
                'type' => 'removed',
                'value' => [
                    'abc' => 12345,
                    'deep' => [
                        'id' => 45,
                    ],
                ],
            ],
            [
                'key' => 'group3',
                'type' => 'added',
                'value' => [
                    'deep' => [
                        'id' => [
                            'number' => 45,
                        ],
                    ],
                    'fee' => 100500,
                ],
            ],
        ];

        $this->assertSame($expected, $diff);
    }
}
