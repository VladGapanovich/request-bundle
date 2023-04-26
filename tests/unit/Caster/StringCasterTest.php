<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Tests\Unit\Caster;

use Generator;
use Jrm\RequestBundle\Caster\StringCaster;
use Jrm\RequestBundle\Exception\InvalidTypeException;
use Jrm\RequestBundle\Parameter\RequestAttribute;
use PHPUnit\Framework\TestCase;

final class StringCasterTest extends TestCase
{
    private StringCaster $subjectUnderTest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subjectUnderTest = new StringCaster();
    }

    /**
     * @test
     */
    public function it_returns_null_if_null_allowed(): void
    {
        $actual = $this->subjectUnderTest->cast(null, $this->createMock(RequestAttribute::class), '', true);

        self::assertNull($actual);
    }

    /**
     * @test
     */
    public function it_cannot_cast_null_to_string(): void
    {
        $this->expectException(InvalidTypeException::class);
        $this->subjectUnderTest->cast(null, $this->createMock(RequestAttribute::class), '', false);
    }

    /**
     * @test
     *
     * @dataProvider provideValidCastableValues
     */
    public function it_casts_value_to_string(mixed $value, string $expected): void
    {
        $actual = $this->subjectUnderTest->cast($value, $this->createMock(RequestAttribute::class), '', false);

        self::assertSame($expected, $actual);
    }

    public static function provideValidCastableValues(): Generator
    {
        yield 'string' => ['string', 'string'];
        yield 'integer' => [1, '1'];
        yield 'float' => [1.0, '1'];
        yield 'true' => [true, '1'];
        yield 'false' => [false, ''];
    }

    /**
     * @test
     *
     * @dataProvider provideInvalidCastableValues
     */
    public function it_cannot_cast_value_to_string(mixed $value): void
    {
        $this->expectException(InvalidTypeException::class);
        $this->subjectUnderTest->cast($value, $this->createMock(RequestAttribute::class), '', false);
    }

    public static function provideInvalidCastableValues(): Generator
    {
        yield 'array' => [[1]];
        yield 'class' => [new class() {
        }];
        yield 'callback' => [static fn (): string => ''];
    }
}
