<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Tests\Unit\Caster;

use Generator;
use Jrm\RequestBundle\Caster\FloatCaster;
use Jrm\RequestBundle\Exception\InvalidTypeException;
use Jrm\RequestBundle\Parameter\RequestAttribute;
use PHPUnit\Framework\TestCase;

final class FloatCasterTest extends TestCase
{
    private FloatCaster $subjectUnderTest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subjectUnderTest = new FloatCaster();
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
    public function it_cannot_cast_null_to_float(): void
    {
        $this->expectException(InvalidTypeException::class);
        $this->subjectUnderTest->cast(null, $this->createMock(RequestAttribute::class), '', false);
    }

    /**
     * @test
     *
     * @dataProvider provideValidCastableValues
     */
    public function it_casts_value_to_float(mixed $value, float $expected): void
    {
        $actual = $this->subjectUnderTest->cast($value, $this->createMock(RequestAttribute::class), '', false);

        self::assertSame($expected, $actual);
    }

    public static function provideValidCastableValues(): Generator
    {
        yield 'float' => [1.0, 1.0];
        yield 'string' => ['1.0', 1.0];
        yield 'int' => [1, 1.0];
    }

    /**
     * @test
     *
     * @dataProvider provideInvalidCastableValues
     */
    public function it_cannot_cast_value_to_float(mixed $value): void
    {
        $this->expectException(InvalidTypeException::class);
        $this->subjectUnderTest->cast($value, $this->createMock(RequestAttribute::class), '', false);
    }

    public static function provideInvalidCastableValues(): Generator
    {
        yield 'string' => ['string'];
        yield 'bool' => [false];
        yield 'array' => [[1]];
        yield 'class' => [new class() {
        }];
        yield 'callback' => [static fn (): string => ''];
    }
}
