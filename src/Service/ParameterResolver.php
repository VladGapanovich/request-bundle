<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Service;

use Jrm\RequestBundle\Registry\ParameterResolverRegistry;
use Jrm\RequestBundle\Resolver\RequestParameterAttributeResolver;
use ReflectionParameter;
use Symfony\Component\HttpFoundation\Request;

final class ParameterResolver
{
    public function __construct(
        private RequestParameterAttributeResolver $requestParameterAttributeResolver,
        private ParameterResolverRegistry $parameterResolverRegistry,
        private ValueToPropertyTypeCaster $valueToPropertyTypeCaster,
    ) {
    }

    public function resolve(Request $request, ReflectionParameter $parameter): mixed
    {
        $attribute = $this->requestParameterAttributeResolver->resolve($parameter);
        $value = $this->parameterResolverRegistry
            ->resolver($attribute)
            ->resolve($request, $parameter, $attribute);

        return $this->valueToPropertyTypeCaster->cast($value, $parameter, $attribute);
    }
}
