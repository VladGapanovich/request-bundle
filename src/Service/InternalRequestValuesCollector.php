<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Service;

use ReflectionClass;
use Throwable;

final class InternalRequestValuesCollector
{
    public function __construct(
        private InternalItemResolver $internalItemResolver,
    ) {
    }

    /**
     * @param class-string $className
     * @param array<array-key, mixed> $data
     *
     * @return array<array-key, mixed>
     */
    public function collect(string $className, array $data): array
    {
        $class = new ReflectionClass($className);
        $constructor = $class->getConstructor();

        if ($constructor !== null && count($constructor->getParameters()) > 0) {
            $parameters = [];

            foreach ($constructor->getParameters() as $parameter) {
                try {
                    $parameters[$parameter->getName()] = $this->internalItemResolver->resolveByParameter($data, $parameter);
                } catch (\Throwable) {
                }
            }

            return $parameters;
        }

        $properties = [];

        foreach ($class->getProperties() as $property) {
            try {
                $properties[$property->getName()] = $this->internalItemResolver->resolveByProperty($data, $property);
            } catch (\Throwable) {
            }
        }

        return $properties;
    }
}
