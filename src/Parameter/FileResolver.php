<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Parameter;

use Jrm\RequestBundle\Exception\UnexpectedAttributeException;
use Jrm\RequestBundle\Service\PropertyAccessor;
use ReflectionParameter;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

final class FileResolver implements ParameterResolver
{
    public function resolve(
        Request $request,
        ReflectionParameter $parameter,
        RequestAttribute $attribute,
    ): mixed {
        if (!$attribute instanceof File) {
            throw new UnexpectedAttributeException(File::class, $attribute::class);
        }

        try {
            return PropertyAccessor::get($request->files->all(), $attribute->name()->value());
        } catch (Throwable $throwable) {
            if ($parameter->isOptional()) {
                return $parameter->getDefaultValue();
            }

            throw $throwable;
        }
    }
}
