<?php

namespace Hexlet\Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

use function Hexlet\Code\genDiff;

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
    public function testSameValues(string $extension): void
    {
        $file = __DIR__ . "/fixtures/{$extension}/same.{$extension}";
        $diff = genDiff($file, $file);
        $this->assertEquals("  host: hexlet.io", $diff);
    }

    #[DataProvider('fileFormatProvider')]
    public function testDifferentValues(string $extension): void
    {
        $file1 = __DIR__ . "/fixtures/{$extension}/changed_first.{$extension}";
        $file2 = __DIR__ . "/fixtures/{$extension}/changed_second.{$extension}";
        $diff = genDiff($file1, $file2);
        $this->assertEquals("- timeout: 50\n+ timeout: 20", $diff);
    }

    #[DataProvider('fileFormatProvider')]
    public function testOnlyInFirst(string $extension): void
    {
        $file1 = __DIR__ . "/fixtures/{$extension}/only_one.{$extension}";
        $file2 = __DIR__ . "/fixtures/{$extension}/same.{$extension}";
        $diff = genDiff($file1, $file2);
        $this->assertEquals("  host: hexlet.io\n- proxy: 123.234.53.22", $diff);
    }

    #[DataProvider('fileFormatProvider')]
    public function testOnlyInSecond(string $extension): void
    {
        $file1 = __DIR__ . "/fixtures/{$extension}/same.{$extension}";
        $file2 = __DIR__ . "/fixtures/{$extension}/only_one.{$extension}";
        $diff = genDiff($file1, $file2);
        $this->assertEquals("  host: hexlet.io\n+ proxy: 123.234.53.22", $diff);
    }

    #[DataProvider('fileFormatProvider')]
    public function testEmptyFiles(string $extension): void
    {
        $file = __DIR__ . "/fixtures/{$extension}/empty.{$extension}";
        $diff = genDiff($file, $file);
        $this->assertEmpty($diff);
    }

    #[DataProvider('fileFormatProvider')]
    public function testFirstEmpty(string $extension): void
    {
        $file1 = __DIR__ . "/fixtures/{$extension}/empty.{$extension}";
        $file2 = __DIR__ . "/fixtures/{$extension}/same.{$extension}";
        $diff = genDiff($file1, $file2);
        $this->assertEquals("+ host: hexlet.io", $diff);
    }

    #[DataProvider('fileFormatProvider')]
    public function testSecondEmpty(string $extension): void
    {
        $file1 = __DIR__ . "/fixtures/{$extension}/same.{$extension}";
        $file2 = __DIR__ . "/fixtures/{$extension}/empty.{$extension}";
        $diff = genDiff($file1, $file2);
        $this->assertEquals("- host: hexlet.io", $diff);
    }

    #[DataProvider('fileFormatProvider')]
    public function testAll(string $extension): void
    {
        $file1 = __DIR__ . "/fixtures/{$extension}/all_first.{$extension}";
        $file2 = __DIR__ . "/fixtures/{$extension}/all_second.{$extension}";
        $diff = genDiff($file1, $file2);
        $this->assertEquals(
            "- follow: false\n  host: hexlet.io\n- proxy: 123.234.53.22\n- timeout: 50\n+ timeout: 20\n+ verbose: true",
            $diff
        );
    }

    #[DataProvider('fileFormatProvider')]
    public function testTypes(string $extension): void
    {
        $file1 = __DIR__ . "/fixtures/{$extension}/to_string_first.{$extension}";
        $file2 = __DIR__ . "/fixtures/{$extension}/to_string_second.{$extension}";
        $diff = genDiff($file1, $file2);
        $this->assertEquals(
            "- enabled: false\n+ enabled: true\n- retries: 3\n+ retries: 3",
            $diff
        );
    }
}
