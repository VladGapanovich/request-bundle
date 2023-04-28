<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Tests\Unit\Attribute;

use Jrm\RequestBundle\Attribute\PathAttribute;
use Jrm\RequestBundle\Attribute\PathAttributeResolver;
use Jrm\RequestBundle\Model\Metadata;
use Jrm\RequestBundle\Model\Name;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\Exception\NoSuchIndexException;

final class PathAttributeResolverTest extends TestCase
{
    private PathAttributeResolver $sut;

    protected function setUp(): void
    {
        $this->sut = new PathAttributeResolver();
    }

    public function test_resolves_required_nested_item(): void
    {
        $request = new Request(attributes: ['productId' => 12]);
        $metadata = new Metadata(new Name('id'), false, null);
        $attribute = new PathAttribute(name: 'productId');
        $expectedResult = 12;

        $result = $this->sut->resolve($request, $metadata, $attribute);

        self::assertSame($expectedResult, $result);
    }

    public function test_resolves_required_item_by_property_name(): void
    {
        $request = new Request(attributes: ['id' => 12]);
        $metadata = new Metadata(new Name('id'), false, null);
        $attribute = new PathAttribute();
        $expectedResult = 12;

        $result = $this->sut->resolve($request, $metadata, $attribute);

        self::assertSame($expectedResult, $result);
    }

    public function test_returns_default_value_when_can_not_resolve_value(): void
    {
        $request = new Request(query: ['id' => 12]);
        $metadata = new Metadata(new Name('name'), true, 'Name');
        $attribute = new PathAttribute();
        $expectedResult = 'Name';

        $result = $this->sut->resolve($request, $metadata, $attribute);

        self::assertSame($expectedResult, $result);
    }

    public function test_throws_an_exception_when_can_not_resolve_value_for_required_item(): void
    {
        // Todo: replace with our exception
        $this->expectException(NoSuchIndexException::class);

        $request = new Request(query: ['id' => 12]);
        $metadata = new Metadata(new Name('name'), false, null);
        $attribute = new PathAttribute();

        $this->sut->resolve($request, $metadata, $attribute);
    }
}
