<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Parameter;

use Jrm\RequestBundle\Exception\UnexpectedAttributeException;
use ReflectionParameter;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

final class PathAttributeResolver implements ParameterResolver
{
    public function resolve(
        Request $request,
        ReflectionParameter $parameter,
        RequestAttribute $attribute,
    ): mixed {
        if (!$attribute instanceof PathAttribute) {
            throw new UnexpectedAttributeException(PathAttribute::class, $attribute::class);
        }

        try {
            return $request->attributes->get($attribute->name()->value());
        } catch (Throwable $throwable) {
            if ($parameter->isOptional()) {
                return $parameter->getDefaultValue();
            }

            throw $throwable;
        }
    }
}
