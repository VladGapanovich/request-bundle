<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Resolver;

use Jrm\RequestBundle\Exception\InvalidRequestParameterException;
use Jrm\RequestBundle\Parameter\RequestAttribute;
use ReflectionAttribute;
use ReflectionParameter;

final class RequestParameterAttributeResolver
{
    public function resolve(ReflectionParameter $parameter): RequestAttribute
    {
        $attribute = $parameter->getAttributes(RequestAttribute::class, ReflectionAttribute::IS_INSTANCEOF)[0]
            ?? throw new InvalidRequestParameterException($parameter->getName());

        return $attribute->newInstance();
    }
}
