<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Parameter;

use Jrm\RequestBundle\Exception\UnexpectedAttributeException;
use Jrm\RequestBundle\Service\PropertyAccessor;
use ReflectionParameter;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

final class FormResolver implements ParameterResolver
{
    public function resolve(
        Request $request,
        ReflectionParameter $parameter,
        RequestAttribute $attribute,
    ): mixed {
        if (!$attribute instanceof Body) {
            throw new UnexpectedAttributeException(Body::class, $attribute::class);
        }

        try {
            return PropertyAccessor::get($request->request->all(), $attribute->path()->valueAsArrayKeyPath());
        } catch (Throwable $throwable) {
            if ($parameter->isOptional()) {
                return $parameter->getDefaultValue();
            }

            throw $throwable;
        }
    }
}
