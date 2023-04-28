<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Attribute;

use Jrm\RequestBundle\Exception\UnexpectedAttributeException;
use Jrm\RequestBundle\Model\Metadata;
use Jrm\RequestBundle\Service\PropertyAccessor;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

final class PathAttributeResolver implements ValueResolver
{
    public function resolve(Request $request, Metadata $metadata, RequestAttribute $attribute): mixed
    {
        if (!$attribute instanceof PathAttribute) {
            throw new UnexpectedAttributeException(PathAttribute::class, $attribute::class);
        }

        try {
            $name = $attribute->name()?->valueAsArrayKey() ?? $metadata->name()->valueAsArrayKey();

            return PropertyAccessor::get($request->attributes->all(), $name);
        } catch (Throwable $throwable) {
            if ($metadata->isOptional()) {
                return $metadata->defaultValue();
            }

            throw $throwable;
        }
    }
}
