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
    public function testEmptyFiles(string $extension): void
    {
        $file = __DIR__ . "/fixtures/{$extension}/empty.{$extension}";
        $diff = genDiff($file, $file);
        $this->assertEquals("{\n\n}", $diff);
    }

    #[DataProvider('fileFormatProvider')]
    public function testTypes(string $extension): void
    {
        $file1 = __DIR__ . "/fixtures/{$extension}/to_string_first.{$extension}";
        $file2 = __DIR__ . "/fixtures/{$extension}/to_string_second.{$extension}";
        $diff = genDiff($file1, $file2);
        $this->assertEquals(
            "{\n  - enabled: false\n  + enabled: true\n  - retries: 3\n  + retries: 3\n}",
            $diff
        );
    }

    #[DataProvider('fileFormatProvider')]
    public function testNestedToEmpty(string $extension): void
    {
        $file1 = __DIR__ . "/fixtures/{$extension}/nested_first.{$extension}";
        $file2 = __DIR__ . "/fixtures/{$extension}/empty.{$extension}";
        $diff = genDiff($file1, $file2);
        $expected = <<<'EXPECTED'
{
  - common: {
        setting1: Value 1
        setting2: 200
        setting3: true
        setting6: {
            doge: {
                wow: 
            }
            key: value
        }
    }
  - group1: {
        baz: bas
        foo: bar
        nest: {
            key: value
        }
    }
  - group2: {
        abc: 12345
        deep: {
            id: 45
        }
    }
}
EXPECTED;
        $this->assertEquals($expected, $diff);
    }

    #[DataProvider('fileFormatProvider')]
    public function testEmptyToNested(string $extension): void
    {
        $file1 = __DIR__ . "/fixtures/{$extension}/empty.{$extension}";
        $file2 = __DIR__ . "/fixtures/{$extension}/nested_second.{$extension}";
        $diff = genDiff($file1, $file2);
        $expected = <<<'EXPECTED'
{
  + common: {
        follow: false
        setting1: Value 1
        setting3: null
        setting4: blah blah
        setting5: {
            key5: value5
        }
        setting6: {
            doge: {
                wow: so much
            }
            key: value
            ops: vops
        }
    }
  + group1: {
        baz: bars
        foo: bar
        nest: str
    }
  + group3: {
        deep: {
            id: {
                number: 45
            }
        }
        fee: 100500
    }
}
EXPECTED;
        $this->assertEquals($expected, $diff);
    }

    #[DataProvider('fileFormatProvider')]
    public function testNested(string $extension): void
    {
        $file1 = __DIR__ . "/fixtures/{$extension}/nested_first.{$extension}";
        $file2 = __DIR__ . "/fixtures/{$extension}/nested_second.{$extension}";
        $diff = genDiff($file1, $file2);
        $expected = <<<'EXPECTED'
{
    common: {
      + follow: false
        setting1: Value 1
      - setting2: 200
      - setting3: true
      + setting3: null
      + setting4: blah blah
      + setting5: {
            key5: value5
        }
        setting6: {
            doge: {
              - wow: 
              + wow: so much
            }
            key: value
          + ops: vops
        }
    }
    group1: {
      - baz: bas
      + baz: bars
        foo: bar
      - nest: {
            key: value
        }
      + nest: str
    }
  - group2: {
        abc: 12345
        deep: {
            id: 45
        }
    }
  + group3: {
        deep: {
            id: {
                number: 45
            }
        }
        fee: 100500
    }
}
EXPECTED;
        $this->assertEquals($expected, $diff);
    }
}
