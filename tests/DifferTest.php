<?php

namespace Hexlet\Tests;

use PHPUnit\Framework\TestCase;

use function Hexlet\Code\genDiff;

class DifferTest extends TestCase
{
    public function testSameValues(): void
    {
        $file = __DIR__ . "/fixtures/same.json";
        $diff = genDiff($file, $file);
        $this->assertEquals("  host: hexlet.io", $diff);
    }

    public function testDifferentValues(): void
    {
        $file1 = __DIR__ . "/fixtures/changed_first.json";
        $file2 = __DIR__ . "/fixtures/changed_second.json";
        $diff = genDiff($file1, $file2);
        $this->assertEquals("- timeout: 50\n+ timeout: 20", $diff);
    }

    public function testOnlyInFirst(): void
    {
        $file1 = __DIR__ . "/fixtures/only_one.json";
        $file2 = __DIR__ . "/fixtures/same.json";
        $diff = genDiff($file1, $file2);
        $this->assertEquals("  host: hexlet.io\n- proxy: 123.234.53.22", $diff);
    }

    public function testOnlyInSecond(): void
    {
        $file1 = __DIR__ . "/fixtures/same.json";
        $file2 = __DIR__ . "/fixtures/only_one.json";
        $diff = genDiff($file1, $file2);
        $this->assertEquals("  host: hexlet.io\n+ proxy: 123.234.53.22", $diff);
    }
    public function testEmptyFiles(): void
    {
        $file = __DIR__ . "/fixtures/empty.json";
        $diff = genDiff($file, $file);
        $this->assertEmpty($diff);
    }

    public function testFirstEmpty(): void
    {
        $file1 = __DIR__ . "/fixtures/empty.json";
        $file2 = __DIR__ . "/fixtures/same.json";
        $diff = genDiff($file1, $file2);
        $this->assertEquals("+ host: hexlet.io", $diff);
    }

    public function testSecondEmpty(): void
    {
        $file1 = __DIR__ . "/fixtures/same.json";
        $file2 = __DIR__ . "/fixtures/empty.json";
        $diff = genDiff($file1, $file2);
        $this->assertEquals("- host: hexlet.io", $diff);
    }

    public function testAll(): void
    {
        $file1 = __DIR__ . "/fixtures/all_first.json";
        $file2 = __DIR__ . "/fixtures/all_second.json";
        $diff = genDiff($file1, $file2);
        $this->assertEquals(
            "- follow: false\n  host: hexlet.io\n- proxy: 123.234.53.22\n- timeout: 50\n+ timeout: 20\n+ verbose: true",
            $diff
        );
    }

    public function testTypes(): void
    {
        $file1 = __DIR__ . "/fixtures/to_string_first.json";
        $file2 = __DIR__ . "/fixtures/to_string_second.json";
        $diff = genDiff($file1, $file2);
        $this->assertEquals(
            "- enabled: false\n+ enabled: true\n- retries: 3\n+ retries: 3",
            $diff
        );
    }
}
