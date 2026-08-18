<?php

namespace Hexlet\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function Hexlet\Code\genDiff;

class JsonTest extends TestCase
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
        $file = __DIR__ . "/../fixtures/{$extension}/empty.{$extension}";

        $result = genDiff($file, $file, 'json');

        $actual = json_decode($result, true, flags: JSON_THROW_ON_ERROR);

        $this->assertEquals([], $actual);
    }

    #[DataProvider('fileFormatProvider')]
    public function testTypes(string $extension): void
    {
        $file1 = __DIR__ . "/../fixtures/{$extension}/to_string_first.{$extension}";
        $file2 = __DIR__ . "/../fixtures/{$extension}/to_string_second.{$extension}";

        $result = genDiff($file1, $file2, 'json');

        $actual = json_decode($result, true, flags: JSON_THROW_ON_ERROR);

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

        $this->assertEquals($expected, $actual);
    }

    #[DataProvider('fileFormatProvider')]
    public function testFirstEmpty(string $extension): void
    {
        $file1 = __DIR__ . "/../fixtures/{$extension}/empty.{$extension}";
        $file2 = __DIR__ . "/../fixtures/{$extension}/only_one.{$extension}";

        $result = genDiff($file1, $file2, 'json');

        $actual = json_decode($result, true, flags: JSON_THROW_ON_ERROR);

        $expected = [
            [
                'key' => 'host',
                'type' => 'added',
                'value' => 'hexlet.io',
            ],
            [
                'key' => 'proxy',
                'type' => 'added',
                'value' => '123.234.53.22',
            ],
        ];

        $this->assertEquals($expected, $actual);
    }

    #[DataProvider('fileFormatProvider')]
    public function testSecondEmpty(string $extension): void
    {
        $file1 = __DIR__ . "/../fixtures/{$extension}/only_one.{$extension}";
        $file2 = __DIR__ . "/../fixtures/{$extension}/empty.{$extension}";

        $result = genDiff($file1, $file2, 'json');

        $actual = json_decode($result, true, flags: JSON_THROW_ON_ERROR);

        $expected = [
            [
                'key' => 'host',
                'type' => 'removed',
                'value' => 'hexlet.io',
            ],
            [
                'key' => 'proxy',
                'type' => 'removed',
                'value' => '123.234.53.22',
            ],
        ];

        $this->assertEquals($expected, $actual);
    }

    #[DataProvider('fileFormatProvider')]
    public function testNested(string $extension): void
    {
        $file1 = __DIR__ . "/../fixtures/{$extension}/nested_first.{$extension}";
        $file2 = __DIR__ . "/../fixtures/{$extension}/nested_second.{$extension}";

        $result = genDiff($file1, $file2, 'json');

        $actual = json_decode($result, true, flags: JSON_THROW_ON_ERROR);

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

        $this->assertEquals($expected, $actual);
    }
}
