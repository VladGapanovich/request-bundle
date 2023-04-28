<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Attribute;

use Jrm\RequestBundle\Exception\UnexpectedAttributeException;
use Jrm\RequestBundle\Model\Metadata;
use Jrm\RequestBundle\Service\PropertyAccessor;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

final class FileResolver implements ValueResolver
{
    public function resolve(Request $request, Metadata $metadata, RequestAttribute $attribute): mixed
    {
        if (!$attribute instanceof File) {
            throw new UnexpectedAttributeException(File::class, $attribute::class);
        }

        try {
            $name = $attribute->name()?->valueAsArrayKey() ?? $metadata->name()->valueAsArrayKey();

            return PropertyAccessor::get($request->files->all(), $name);
        } catch (Throwable $throwable) {
            if ($metadata->isOptional()) {
                return $metadata->defaultValue();
            }

            throw $throwable;
        }
    }
}
