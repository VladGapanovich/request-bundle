<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\ArgumentResolver;

use Jrm\RequestBundle\Exception\UnsupportedTypeException;
use Jrm\RequestBundle\Factory\RequestFactory;
use Jrm\RequestBundle\MapRequest;
use Jrm\RequestBundle\Service\RequestDataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final readonly class RequestResolver implements ValueResolverInterface
{
    public function __construct(
        private RequestDataCollector $requestDataCollector,
        private RequestFactory $requestFactory,
    ) {
    }

    /**
     * @return object[]
     */
    public function resolve(Request $request, ArgumentMetadata $argument): array
    {
        $attributes = $argument->getAttributes(MapRequest::class, ArgumentMetadata::IS_INSTANCEOF);

        if ($attributes === []) {
            return [];
        }

        /** @var MapRequest $attribute */
        $attribute = $attributes[0];
        $type = (string) $argument->getType();

        if (!class_exists($type)) {
            throw new UnsupportedTypeException($argument->getName(), MapRequest::class, 'class-string', $type);
        }

        $requestData = $this->requestDataCollector->collect($type, $request);

        return [$this->requestFactory->create($attribute, $type, $requestData)];
    }
}
