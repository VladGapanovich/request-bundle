<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Service;

use Jrm\RequestBundle\Factory\MetadataFactory;
use Jrm\RequestBundle\Registry\InternalValueResolverRegistry;
use Jrm\RequestBundle\Resolver\InternalRequestAttributeResolver;
use ReflectionParameter;
use ReflectionProperty;

final class InternalItemResolver
{
    public function __construct(
        private InternalRequestAttributeResolver $internalRequestAttributeResolver,
        private InternalValueResolverRegistry $internalValueResolverRegistry,
        private MetadataFactory $metadataFactory,
    ) {
    }

    /**
     * @param array<array-key, mixed> $data
     */
    public function resolveByParameter(array $data, ReflectionParameter $parameter): mixed
    {
        $attribute = $this->internalRequestAttributeResolver->resolve($parameter);
        $metadata = $this->metadataFactory->createByParameter($parameter);

        return $this->internalValueResolverRegistry
            ->resolver($attribute)
            ->resolve($data, $metadata, $attribute);
    }

    /**
     * @param array<array-key, mixed> $data
     */
    public function resolveByProperty(array $data, ReflectionProperty $property): mixed
    {
        $attribute = $this->internalRequestAttributeResolver->resolve($property);
        $metadata = $this->metadataFactory->createByProperty($property);

        return $this->internalValueResolverRegistry
            ->resolver($attribute)
            ->resolve($data, $metadata, $attribute);
    }
}
