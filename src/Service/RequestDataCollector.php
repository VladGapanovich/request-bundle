<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Service;

use ReflectionClass;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

final class RequestDataCollector
{
    public function __construct(
        private ItemResolver $itemResolver,
    ) {
    }

    /**
     * @param class-string $className
     *
     * @return array<array-key, mixed>
     *
     * @throws ReflectionException
     */
    public function collect(string $className, Request $request): array
    {
        $class = new ReflectionClass($className);
        $constructor = $class->getConstructor();

        if ($constructor !== null && count($constructor->getParameters()) > 0) {
            $parameters = [];

            foreach ($constructor->getParameters() as $parameter) {
                try {
                    $parameters[$parameter->getName()] = $this->itemResolver->resolveByParameter($request, $parameter);
                } catch (Throwable) {
                }
            }

            return $parameters;
        }

        $properties = [];

        foreach ($class->getProperties() as $property) {
            try {
                $properties[$property->getName()] = $this->itemResolver->resolveByProperty($request, $property);
            } catch (Throwable) {
            }
        }

        return $properties;
    }
}
