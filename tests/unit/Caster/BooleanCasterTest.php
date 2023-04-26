<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Tests\Unit\Caster;

use Generator;
use Jrm\RequestBundle\Caster\BooleanCaster;
use Jrm\RequestBundle\Exception\InvalidTypeException;
use Jrm\RequestBundle\Parameter\RequestAttribute;
use PHPUnit\Framework\TestCase;

final class BooleanCasterTest extends TestCase
{
    private BooleanCaster $subjectUnderTest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subjectUnderTest = new BooleanCaster();
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
    public function it_cannot_cast_null_to_bool(): void
    {
        $this->expectException(InvalidTypeException::class);
        $this->subjectUnderTest->cast(null, $this->createMock(RequestAttribute::class), '', false);
    }

    /**
     * @test
     *
     * @dataProvider provideValidCastableValues
     */
    public function it_casts_value_to_bool(mixed $value, bool $expected): void
    {
        $actual = $this->subjectUnderTest->cast($value, $this->createMock(RequestAttribute::class), '', false);

        self::assertSame($expected, $actual);
    }

    public static function provideValidCastableValues(): Generator
    {
        yield 'bool' => [true, true];
        yield 'string' => ['false', false];
        yield 'empty string' => ['', false];
        yield 'int' => [1, true];
        yield 'float' => [0.0, false];
    }

    /**
     * @test
     *
     * @dataProvider provideInvalidCastableValues
     */
    public function it_cannot_cast_value_to_bool(mixed $value): void
    {
        $this->expectException(InvalidTypeException::class);
        $this->subjectUnderTest->cast($value, $this->createMock(RequestAttribute::class), '', false);
    }

    public static function provideInvalidCastableValues(): Generator
    {
        yield 'string' => ['string'];
        yield 'array' => [[1]];
        yield 'class' => [new class() {
        }];
        yield 'callback' => [static fn (): string => ''];
    }
}
