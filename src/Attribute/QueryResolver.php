<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Attribute;

use Jrm\RequestBundle\Exception\UnexpectedAttributeException;
use Jrm\RequestBundle\Model\Metadata;
use Jrm\RequestBundle\Service\PropertyAccessor;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

final class QueryResolver implements ValueResolver
{
    public function resolve(Request $request, Metadata $metadata, RequestAttribute $attribute): mixed
    {
        if (!$attribute instanceof Query) {
            throw new UnexpectedAttributeException(Query::class, $attribute::class);
        }

        try {
            $path = $attribute->path()?->valueAsArrayKeyPath() ?? $metadata->name()->valueAsArrayKey();

            return PropertyAccessor::get($request->query->all(), $path);
        } catch (Throwable $throwable) {
            if ($metadata->isOptional()) {
                return $metadata->defaultValue();
            }

            throw $throwable;
        }
    }
}
