<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Tests\Unit\Model;

use Generator;
use InvalidArgumentException;
use Jrm\RequestBundle\Model\Path;
use PHPUnit\Framework\TestCase;

final class PathTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider provideValidPaths
     *
     * @doesNotPerformAssertions
     */
    public function it_creates_path(string $path): void
    {
        new Path($path);
    }

    /**
     * @return Generator<string, array<int, string>>
     */
    public static function provideValidPaths(): Generator
    {
        yield '4056e765-869c-4707-aaf0-6e2ae6cc5112' => ['a'];
        yield 'c2362e69-520e-4e95-871a-10d91518ae8b' => ['abc.def'];
        yield '48268dc6-bd23-477b-b70c-6ba5fe920ff1' => ['a.bcd.ef'];
    }

    /**
     * @test
     *
     * @dataProvider provideInvalidPaths
     */
    public function it_throws_an_exception_on_invalid_path_length(string $path): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Path($path);
    }

    /**
     * @return Generator<string, array<int, string>>
     */
    public static function provideInvalidPaths(): Generator
    {
        yield '4056e765-869c-4707-aaf0-6e2ae6cc5112' => [''];
        yield 'b0cc7de0-faf4-480b-a4db-6a6bf4d3a04e' => ['.'];
        yield '9dd5fd4b-9ccf-43cc-9b4a-6a2e429e5b94' => ['['];
        yield '4f66b84b-31b8-4e96-bc31-2dfbe13747f7' => [']'];
        yield '3df1a53f-0270-44a5-85d4-af60b4095405' => ['.property'];
        yield '48abbb02-1223-414a-b686-114cef33dd3e' => ['.[property'];
        yield '72b016f7-6495-40a3-a595-7c9b8b97ab18' => ['.]property'];
        yield '5226775a-a297-48dd-a16f-d9f954a9230c' => ['..property'];
        yield 'ce639804-f0c8-40d1-89bd-1d52057943de' => ['[property'];
        yield '85f78d1c-bb04-4788-8a78-ed1248beb0ec' => ['[[property'];
        yield '4c6d5bb8-ca7c-45a2-ac34-c648a71ae81e' => ['[.property'];
        yield '51691137-309f-4f07-8727-d3ddcf01bd87' => ['[]property'];
        yield '57b5f4ea-6aae-4f9c-a11f-26215f29a5c9' => [']property'];
        yield 'dc7ae315-1d42-4e73-b219-2dceb7c57e52' => ['][property'];
        yield '38e48f59-01d6-4481-8906-3962e2f71e07' => ['].property'];
        yield '625258a8-7020-4f55-89cd-ce2b8cf1c696' => [']]property'];
        yield 'fe371fd3-e7cc-41c8-807d-2ef8a702ba9a' => ['property.'];
        yield 'ebac4314-b4bd-428d-a9cd-e324edeae2cf' => ['property.['];
        yield 'ab739fea-94a1-4fc4-a631-ac5dc39dce48' => ['property.]'];
        yield '70768a8f-4378-4248-93b5-e50d32c9f33e' => ['property..'];
        yield 'b39c6369-afa3-4e11-a9fc-4060d793b927' => ['property['];
        yield '467409c9-93c9-43f7-99fc-90e9f7ec9f30' => ['property[['];
        yield 'b6098ac5-27f0-4800-af9b-7cfeb5ad19fc' => ['property[.'];
        yield '524c9991-8b82-428b-a173-7135fc4a24c4' => ['property[]'];
        yield 'f62a4797-c8e9-459c-bf72-5004ee634e29' => ['property]'];
        yield 'c727df03-e79e-4a5b-a653-ee0e3c42fbb9' => ['property]['];
        yield '50eda840-2829-41b6-b863-6f42bd705d4d' => ['property].'];
        yield '4c135735-a190-4b6f-9631-fc175ecdadae' => ['property]]'];
        yield '9becae08-1383-472a-b55a-200d1a0a0833' => ['prop.erty]'];
        yield '9cfabdc6-eb50-46e6-a28e-58322919557c' => ['prop.ert[y'];
        yield '869697fe-1ccb-48a6-8e8a-f37ad3aef5ab' => ['prop.ert[]y'];
        yield '52e2076a-ea7c-4df2-a973-0aba37c40bc1' => ['propert[]y'];
        yield '58df1b40-21fd-4fa2-a2e1-b1f33807cf9f' => ['proper]ty'];
        yield '3d94416d-7d49-4fee-a8c3-a6a58559125e' => ['pro[perty'];
    }
}
