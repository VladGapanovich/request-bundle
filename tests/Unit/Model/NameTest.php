<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Tests\Unit\Model;

use Generator;
use InvalidArgumentException;
use Jrm\RequestBundle\Model\Name;
use PHPUnit\Framework\TestCase;

final class NameTest extends TestCase
{
    /**
     * @doesNotPerformAssertions
     */
    public function test_creates_name(): void
    {
        new Name('a');
    }

    /**
     * @dataProvider provideInvalidNames
     */
    public function test_can_not_create_name_from_invalid_string(string $name): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Name($name);
    }

    /**
     * @return Generator<string, string[]>
     */
    public static function provideInvalidNames(): Generator
    {
        yield '57b5f4ea-6aae-4f9c-a11f-26215f29a5c9' => [''];
        yield 'dc7ae315-1d42-4e73-b219-2dceb7c57e52' => ['.'];
        yield '38e48f59-01d6-4481-8906-3962e2f71e07' => ['['];
        yield '625258a8-7020-4f55-89cd-ce2b8cf1c696' => [']'];
        yield 'fe371fd3-e7cc-41c8-807d-2ef8a702ba9a' => ['.name'];
        yield 'ebac4314-b4bd-428d-a9cd-e324edeae2cf' => ['name.'];
        yield 'ab739fea-94a1-4fc4-a631-ac5dc39dce48' => ['[name'];
        yield '70768a8f-4378-4248-93b5-e50d32c9f33e' => [']name'];
        yield 'b39c6369-afa3-4e11-a9fc-4060d793b927' => ['name['];
        yield '467409c9-93c9-43f7-99fc-90e9f7ec9f30' => ['name]'];
        yield 'b6098ac5-27f0-4800-af9b-7cfeb5ad19fc' => [']name]'];
        yield '524c9991-8b82-428b-a173-7135fc4a24c4' => ['[name['];
        yield 'f62a4797-c8e9-459c-bf72-5004ee634e29' => ['[]name'];
        yield 'c727df03-e79e-4a5b-a653-ee0e3c42fbb9' => ['name[]'];
        yield '50eda840-2829-41b6-b863-6f42bd705d4d' => ['[nam]e'];
        yield '4c135735-a190-4b6f-9631-fc175ecdadae' => ['n[ame]'];
    }
}
