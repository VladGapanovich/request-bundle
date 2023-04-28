<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Attribute\Internal;

use Jrm\RequestBundle\Exception\UnexpectedAttributeException;
use Jrm\RequestBundle\Model\Metadata;
use Jrm\RequestBundle\Service\PropertyAccessor;
use Throwable;

/**
 * @internal
 */
final class InternalItemResolver implements InternalValueResolver
{
    /**
     * @param array<string, mixed> $data
     */
    public function resolve(array $data, Metadata $metadata, InternalRequestAttribute $attribute): mixed
    {
        if (!$attribute instanceof Item) {
            throw new UnexpectedAttributeException(Item::class, $attribute::class);
        }

        try {
            $path = $attribute->path()?->valueAsArrayKeyPath() ?? $metadata->name()->valueAsArrayKey();

            return PropertyAccessor::get($data, $path);
        } catch (Throwable $throwable) {
            if ($metadata->isOptional()) {
                return $metadata->defaultValue();
            }

            throw $throwable;
        }
    }
}
