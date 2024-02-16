<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Tests\Integration\ArgumentResolver;

use Jrm\RequestBundle\ArgumentResolver\RequestResolver;
use Jrm\RequestBundle\MapRequest;
use Jrm\RequestBundle\Tests\Fixture\ComplicatedRequestStub;
use Jrm\RequestBundle\Tests\Fixture\Image;
use Jrm\RequestBundle\Tests\Fixture\Ingredient;
use Jrm\RequestBundle\Tests\Fixture\Product;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

/**
 * @covers \Jrm\RequestBundle\ArgumentResolver\RequestResolver
 */
final class RequestResolverTest extends KernelTestCase
{
    private RequestResolver $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $sut = self::getContainer()->get('jrm.request.argument_resolver.request_resolver');
        assert($sut instanceof RequestResolver);

        $this->sut = $sut;
    }

    public function test_resolvers_request(): void
    {
        $argumentMetadata = new ArgumentMetadata(
            '$request',
            ComplicatedRequestStub::class,
            false,
            false,
            null,
            false,
            [new MapRequest()],
        );

        $symfonyRequest = new Request(
            attributes: ['id' => '25921208-623d-4cf7-a743-0b50a684691d'],
            cookies: ['authToken' => 'Bearer token'],
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode([
                'name' => 'Test name',
                'tags' => ['tag1', 'tag2'],
                'product' => [
                    'id' => '39306b99-26e8-49cc-8598-5b4481287c67',
                    'tags' => ['tag1', 'tag2'],
                    'image' => [
                        'id' => 'eca7af71-442d-4f59-ab89-0ef45b56fcde',
                    ],
                    'ingredients' => [
                        [
                            'id' => '7c00aee4-cd19-499b-8261-3192253fe664',
                            'image' => [
                                'id' => 'b9f53b4d-f3c5-4ac7-81cf-5c146d2b2808',
                            ],
                        ],
                        [
                            'id' => '18aec632-553f-450e-9d0f-632bc7228c54',
                            'image' => [
                                'id' => 'b42a4324-d53a-421f-a6e7-56eeccf69395',
                            ],
                        ],
                    ]
                ]
            ], JSON_THROW_ON_ERROR),
        );

        $requests = [...$this->sut->resolve($symfonyRequest, $argumentMetadata)];

        $expectedRequest = new ComplicatedRequestStub(
            '25921208-623d-4cf7-a743-0b50a684691d',
            new Product(
                '39306b99-26e8-49cc-8598-5b4481287c67',
                ['tag1', 'tag2'],
                new Image('eca7af71-442d-4f59-ab89-0ef45b56fcde'),
                [
                    new Ingredient(
                        '7c00aee4-cd19-499b-8261-3192253fe664',
                        new Image('b9f53b4d-f3c5-4ac7-81cf-5c146d2b2808'),
                    ),
                    new Ingredient(
                        '18aec632-553f-450e-9d0f-632bc7228c54',
                        new Image('b42a4324-d53a-421f-a6e7-56eeccf69395'),
                    ),
                ],
            ),
            'application/json',
            ['tag1', 'tag2'],
            'Test name',
            'Bearer token',
        );
        self::assertCount(1, $requests);
        self::assertEquals($expectedRequest, $requests[0]);
    }
}
