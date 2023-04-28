<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Tests\Unit\Model;

use Generator;
use InvalidArgumentException;
use Jrm\RequestBundle\Model\Source;
use PHPUnit\Framework\TestCase;

final class SourceTest extends TestCase
{
    /**
     * @dataProvider provideSource
     *
     * @doesNotPerformAssertions
     */
    public function test_creates_source(string $source): void
    {
        new Source($source);
    }

    /**
     * @return Generator<string, string[]>
     */
    public static function provideSource(): Generator
    {
        yield 'Body source' => [Source::BODY];

        yield 'Query source' => [Source::QUERY];
    }

    public function test_can_not_create_with_invalid_source(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Source('form');
    }
}
