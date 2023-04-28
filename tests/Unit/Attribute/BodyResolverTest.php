<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Tests\Unit\Attribute;

use Jrm\RequestBundle\Attribute\Body;
use Jrm\RequestBundle\Attribute\BodyResolver;
use Jrm\RequestBundle\Model\Metadata;
use Jrm\RequestBundle\Model\Name;
use Jrm\RequestBundle\Service\RequestBodyGetter;
use Jrm\RequestBundle\Validator\RequestFormatValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\Exception\NoSuchIndexException;
use Symfony\Component\Serializer\Encoder\JsonDecode;

final class BodyResolverTest extends TestCase
{
    private BodyResolver $sut;

    protected function setUp(): void
    {
        $this->sut = new BodyResolver(
            new RequestBodyGetter(
                new RequestFormatValidator(),
                new JsonDecode([JsonDecode::ASSOCIATIVE => true]),
            ),
        );
    }

    public function test_resolves_required_nested_item(): void
    {
        $request = new Request(
            server: ['CONTENT_TYPE' => 'application/json'],
            content: '{"product": {"name": "Name"}}',
        );
        $metadata = new Metadata(new Name('name'), false, null);
        $attribute = new Body(path: 'product.name');
        $expectedResult = 'Name';

        $result = $this->sut->resolve($request, $metadata, $attribute);

        self::assertSame($expectedResult, $result);
    }

    public function test_resolves_required_item_by_property_name(): void
    {
        $request = new Request(
            server: ['CONTENT_TYPE' => 'application/json'],
            content: '{"name": "Name"}',
        );
        $metadata = new Metadata(new Name('name'), false, null);
        $attribute = new Body();
        $expectedResult = 'Name';

        $result = $this->sut->resolve($request, $metadata, $attribute);

        self::assertSame($expectedResult, $result);
    }

    public function test_returns_default_value_when_can_not_resolve_value(): void
    {
        $request = new Request(
            server: ['CONTENT_TYPE' => 'application/json'],
            content: '{"name": "Name"}',
        );
        $metadata = new Metadata(new Name('id'), true, 12);
        $attribute = new Body();
        $expectedResult = 12;

        $result = $this->sut->resolve($request, $metadata, $attribute);

        self::assertSame($expectedResult, $result);
    }

    public function test_throws_an_exception_when_can_not_resolve_value_for_required_item(): void
    {
        // Todo: replace with our exception
        $this->expectException(NoSuchIndexException::class);

        $request = new Request(
            server: ['CONTENT_TYPE' => 'application/json'],
            content: '{"name": "Name"}',
        );
        $metadata = new Metadata(new Name('id'), false, null);
        $attribute = new Body();

        $this->sut->resolve($request, $metadata, $attribute);
    }
}
