<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Factory;

use Jrm\RequestBundle\Service\ParameterResolver;
use ReflectionClass;
use ReflectionParameter;
use Symfony\Component\HttpFoundation\Request;

final class RequestFactory
{
    public function __construct(
        private ParameterResolver $parameterResolver,
    ) {
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $className
     *
     * @return T
     */
    public function create(string $className, Request $symfonyRequest): object
    {
        $class = new ReflectionClass($className);
        $constructor = $class->getConstructor();

        if ($constructor === null) {
            return $class->newInstance();
        }

        $parameters = \array_map(
            fn (ReflectionParameter $parameter): mixed => $this->parameterResolver->resolve($symfonyRequest, $parameter),
            $constructor->getParameters(),
        );

        return $class->newInstanceArgs($parameters);
    }
}
