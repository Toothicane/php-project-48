<?php

namespace Hexlet\Tests;

use PHPUnit\Framework\TestCase;
use RuntimeException;

use function Hexlet\Code\getFormatter;

class FormattersTest extends TestCase
{
    public function testStylishFormatter(): void
    {
        $formatter = getFormatter('stylish');
        $this->assertIsCallable($formatter);

        $diff = [
            [
                'key' => 'foo',
                'type' => 'added',
                'value' => 'bar',
            ],
        ];

        $this->assertSame(
            "{\n  + foo: bar\n}",
            $formatter($diff)
        );
    }

    public function testPlainFormatter(): void
    {
        $formatter = getFormatter('plain');
        $this->assertIsCallable($formatter);

        $diff = [
            [
                'key' => 'foo',
                'type' => 'added',
                'value' => 'bar',
            ],
        ];

        $this->assertSame(
            "Property 'foo' was added with value: 'bar'",
            $formatter($diff)
        );
    }

    public function testUnknownFormatter(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unknown format: blah');

        getFormatter('blah');
    }
}
