<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Factory;

use Jrm\RequestBundle\Parameter\ItemResolver;
use Jrm\RequestBundle\Resolver\InternalRequestParameterAttributeResolver;
use Jrm\RequestBundle\Service\ValueToPropertyTypeCaster;
use ReflectionClass;
use ReflectionParameter;

final class ItemFactory
{
    public function __construct(
        private InternalRequestParameterAttributeResolver $internalRequestParameterAttributeResolver,
        private ItemResolver $itemResolver,
        private ValueToPropertyTypeCaster $valueToPropertyTypeCaster,
    ) {
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $type
     * @param array<string, mixed> $data
     *
     * @return T
     */
    public function create(string $type, array $data): object
    {
        $class = new ReflectionClass($type);
        $constructor = $class->getConstructor();

        if ($constructor === null) {
            return $class->newInstance();
        }

        $parameters = array_map(
            function (ReflectionParameter $parameter) use ($data): mixed {
                $attribute = $this->internalRequestParameterAttributeResolver->resolve($parameter);
                $value = $this->itemResolver->resolve($data, $parameter, $attribute->path());

                return $this->valueToPropertyTypeCaster->cast($value, $parameter, $attribute);
            },
            $constructor->getParameters(),
        );

        return $class->newInstanceArgs($parameters);
    }
}
