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

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        $attributes = $argument->getAttributes(MapRequest::class, ArgumentMetadata::IS_INSTANCEOF);

        return $attributes !== [];
    }

    /**
     * @return iterable<object>
     */
    public function resolve(Request $symfonyRequest, ArgumentMetadata $argument): iterable
    {
        $attributes = $argument->getAttributes(MapRequest::class, ArgumentMetadata::IS_INSTANCEOF);

        /** @var MapRequest $attribute */
        $attribute = $attributes[0];
        $type = (string) $argument->getType();

        if (!class_exists($type)) {
            throw new UnsupportedTypeException($argument->getName(), MapRequest::class, 'class-string', $type);
        }

        $requestData = $this->requestDataCollector->collect($type, $symfonyRequest);

        yield $this->requestFactory->create($attribute, $type, $requestData);
    }
}
