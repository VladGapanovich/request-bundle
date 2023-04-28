<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Service;

use Jrm\RequestBundle\Factory\MetadataFactory;
use Jrm\RequestBundle\Registry\ValueResolverRegistry;
use Jrm\RequestBundle\Resolver\RequestAttributeResolver;
use ReflectionParameter;
use ReflectionProperty;
use Symfony\Component\HttpFoundation\Request;

final class ItemResolver
{
    public function __construct(
        private RequestAttributeResolver $requestAttributeResolver,
        private ValueResolverRegistry $valueResolverRegistry,
        private MetadataFactory $metadataFactory,
    ) {
    }

    public function resolveByParameter(Request $request, ReflectionParameter $parameter): mixed
    {
        $attribute = $this->requestAttributeResolver->resolve($parameter);
        $metadata = $this->metadataFactory->createByParameter($parameter);

        return $this->valueResolverRegistry
            ->resolver($attribute)
            ->resolve($request, $metadata, $attribute);
    }

    public function resolveByProperty(Request $request, ReflectionProperty $property): mixed
    {
        $attribute = $this->requestAttributeResolver->resolve($property);
        $metadata = $this->metadataFactory->createByProperty($property);

        return $this->valueResolverRegistry
            ->resolver($attribute)
            ->resolve($request, $metadata, $attribute);
    }
}
