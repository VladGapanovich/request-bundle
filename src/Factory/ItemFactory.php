<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Factory;

use Jrm\RequestBundle\Attribute\Internal\InternalItemResolver;
use Jrm\RequestBundle\Resolver\InternalRequestAttributeResolver;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;

final class ItemFactory
{
    public function __construct(
        private InternalRequestAttributeResolver $internalRequestParameterAttributeResolver,
        private InternalItemResolver $itemResolver,
        private MetadataFactory $metadataFactory,
    ) {
    }

    /**
     * @param class-string $type
     * @param array<array-key, mixed> $data
     */
    public function create(string $type, array $data): object
    {
        $class = new ReflectionClass($type);
        $constructor = $class->getConstructor();

        if (!$constructor instanceof ReflectionMethod) {
            return $class->newInstance();
        }

        $parameters = array_map(
            function (ReflectionParameter $parameter) use ($data): mixed {
                $attribute = $this->internalRequestParameterAttributeResolver->resolve($parameter);
                $metadata = $this->metadataFactory->createByParameter($parameter);

                return $this->itemResolver->resolve($data, $metadata, $attribute);
            },
            $constructor->getParameters(),
        );

        return $class->newInstanceArgs($parameters);
    }
}
