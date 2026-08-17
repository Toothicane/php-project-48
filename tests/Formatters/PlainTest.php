<?php

namespace Hexlet\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function Hexlet\Code\genDiff;

class PlainTest extends TestCase
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
        $diff = genDiff($file, $file, 'plain');
        $this->assertEquals("", $diff);
    }

    #[DataProvider('fileFormatProvider')]
    public function testTypes(string $extension): void
    {
        $file1 = __DIR__ . "/../fixtures/{$extension}/to_string_first.{$extension}";
        $file2 = __DIR__ . "/../fixtures/{$extension}/to_string_second.{$extension}";
        $diff = genDiff($file1, $file2, 'plain');
        $expected = <<<'EXPECTED'
Property 'enabled' was updated. From false to true
Property 'retries' was updated. From 3 to '3'
EXPECTED;
        $this->assertEquals($expected, $diff);
    }

    #[DataProvider('fileFormatProvider')]
    public function testFirstEmpty(string $extension): void
    {
        $file1 = __DIR__ . "/../fixtures/{$extension}/empty.{$extension}";
        $file2 = __DIR__ . "/../fixtures/{$extension}/only_one.{$extension}";
        $diff = genDiff($file1, $file2, 'plain');
        $expected = <<<'EXPECTED'
Property 'host' was added with value: 'hexlet.io'
Property 'proxy' was added with value: '123.234.53.22'
EXPECTED;
        $this->assertEquals($expected, $diff);
    }

    #[DataProvider('fileFormatProvider')]
    public function testSecondEmpty(string $extension): void
    {
        $file1 = __DIR__ . "/../fixtures/{$extension}/only_one.{$extension}";
        $file2 = __DIR__ . "/../fixtures/{$extension}/empty.{$extension}";
        $diff = genDiff($file1, $file2, 'plain');
        $expected = <<<'EXPECTED'
Property 'host' was removed
Property 'proxy' was removed
EXPECTED;
        $this->assertEquals($expected, $diff);
    }

    #[DataProvider('fileFormatProvider')]
    public function testNested(string $extension): void
    {
        $file1 = __DIR__ . "/../fixtures/{$extension}/nested_first.{$extension}";
        $file2 = __DIR__ . "/../fixtures/{$extension}/nested_second.{$extension}";
        $diff = genDiff($file1, $file2, 'plain');
        $expected = <<<'EXPECTED'
Property 'common.follow' was added with value: false
Property 'common.setting2' was removed
Property 'common.setting3' was updated. From true to null
Property 'common.setting4' was added with value: 'blah blah'
Property 'common.setting5' was added with value: [complex value]
Property 'common.setting6.doge.wow' was updated. From '' to 'so much'
Property 'common.setting6.ops' was added with value: 'vops'
Property 'group1.baz' was updated. From 'bas' to 'bars'
Property 'group1.nest' was updated. From [complex value] to 'str'
Property 'group2' was removed
Property 'group3' was added with value: [complex value]
EXPECTED;

        $this->assertEquals($expected, $diff);
    }
}
