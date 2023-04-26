<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Resolver;

use Jrm\RequestBundle\Exception\InvalidInternalRequestParameterException;
use Jrm\RequestBundle\Parameter\InternalRequestAttribute;
use ReflectionAttribute;
use ReflectionParameter;

final class InternalRequestParameterAttributeResolver
{
    public function resolve(ReflectionParameter $parameter): InternalRequestAttribute
    {
        $attribute = $parameter->getAttributes(InternalRequestAttribute::class, ReflectionAttribute::IS_INSTANCEOF)[0]
            ?? throw new InvalidInternalRequestParameterException($parameter->getName());

        return $attribute->newInstance();
    }
}
