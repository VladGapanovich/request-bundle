<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Resolver;

use Jrm\RequestBundle\Attribute\RequestAttribute;
use Jrm\RequestBundle\Exception\InvalidRequestItemException;
use ReflectionAttribute;
use ReflectionParameter;
use ReflectionProperty;

final class RequestAttributeResolver
{
    public function resolve(ReflectionParameter|ReflectionProperty $parameter): RequestAttribute
    {
        $attribute = $parameter->getAttributes(RequestAttribute::class, ReflectionAttribute::IS_INSTANCEOF)[0]
            ?? throw new InvalidRequestItemException($parameter->getName());

        return $attribute->newInstance();
    }
}
