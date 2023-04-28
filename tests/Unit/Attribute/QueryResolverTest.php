<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Tests\Unit\Attribute;

use Jrm\RequestBundle\Attribute\Query;
use Jrm\RequestBundle\Attribute\QueryResolver;
use Jrm\RequestBundle\Model\Metadata;
use Jrm\RequestBundle\Model\Name;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\Exception\NoSuchIndexException;

final class QueryResolverTest extends TestCase
{
    private QueryResolver $sut;

    protected function setUp(): void
    {
        $this->sut = new QueryResolver();
    }

    public function test_resolves_required_nested_item(): void
    {
        $request = new Request(query: ['product' => ['name' => 'Name']]);
        $metadata = new Metadata(new Name('name'), false, null);
        $attribute = new Query(path: 'product.name');
        $expectedResult = 'Name';

        $result = $this->sut->resolve($request, $metadata, $attribute);

        self::assertSame($expectedResult, $result);
    }

    public function test_resolves_required_item_by_property_name(): void
    {
        $request = new Request(query: ['name' => 'Name']);
        $metadata = new Metadata(new Name('name'), false, null);
        $attribute = new Query();
        $expectedResult = 'Name';

        $result = $this->sut->resolve($request, $metadata, $attribute);

        self::assertSame($expectedResult, $result);
    }

    public function test_returns_default_value_when_can_not_resolve_value(): void
    {
        $request = new Request(query: ['name' => 'Name']);
        $metadata = new Metadata(new Name('id'), true, 12);
        $attribute = new Query();
        $expectedResult = 12;

        $result = $this->sut->resolve($request, $metadata, $attribute);

        self::assertSame($expectedResult, $result);
    }

    public function test_throws_an_exception_when_can_not_resolve_value_for_required_item(): void
    {
        // Todo: replace with our exception
        $this->expectException(NoSuchIndexException::class);

        $request = new Request(query: ['name' => 'Name']);
        $metadata = new Metadata(new Name('id'), false, null);
        $attribute = new Query();

        $this->sut->resolve($request, $metadata, $attribute);
    }
}
