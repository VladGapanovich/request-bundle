<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\ArgumentResolver;

use Jrm\RequestBundle\Factory\RequestFactory;
use Jrm\RequestBundle\MapRequest;
use Jrm\RequestBundle\Validator\RequestValidator;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final class RequestResolver implements ArgumentValueResolverInterface
{
    public function __construct(
        private RequestFactory $requestFactory,
        private RequestValidator $requestValidator,
    ) {
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        $attributes = $argument->getAttributes(MapRequest::class, ArgumentMetadata::IS_INSTANCEOF);

        return count($attributes) > 0;
    }

    /**
     * @return iterable<object>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $type = (string) $argument->getType();

        if (!class_exists($type)) {
            throw new RuntimeException('Cannot cast request to non class type');
        }

        $clientRequest = $this->requestFactory->create($type, $request);
        $this->requestValidator->validate($clientRequest);

        yield $clientRequest;
    }
}
