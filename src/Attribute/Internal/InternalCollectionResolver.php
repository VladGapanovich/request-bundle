<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Attribute\Internal;

use Jrm\RequestBundle\Attribute\Collection;
use Jrm\RequestBundle\Exception\InvalidTypeException;
use Jrm\RequestBundle\Exception\UnexpectedAttributeException;
use Jrm\RequestBundle\Model\Metadata;
use Jrm\RequestBundle\Service\InternalRequestValuesCollector;
use Jrm\RequestBundle\Service\PropertyAccessor;
use Throwable;

/**
 * @internal
 */
final class InternalCollectionResolver implements InternalValueResolver
{
    public function __construct(
        private InternalRequestValuesCollector $internalRequestValuesCollector,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public function resolve(array $data, Metadata $metadata, InternalRequestAttribute $attribute): mixed
    {
        if (!$attribute instanceof Collection) {
            throw new UnexpectedAttributeException(Collection::class, $attribute::class);
        }

        try {
            $path = $attribute->path()?->valueAsArrayKeyPath() ?? $metadata->name()->valueAsArrayKey();
            $value = PropertyAccessor::get($data, $path);

            if (!is_array($value)) {
                throw new InvalidTypeException('array', \get_debug_type($value));
            }

            $collection = [];

            foreach ($value as $key => $item) {
                if (!is_array($item)) {
                    throw new InvalidTypeException('array', \get_debug_type($item));
                }

                $collection[$key] = $this->internalRequestValuesCollector->collect($attribute->type(), $item);
            }

            return $collection;
        } catch (Throwable $throwable) {
            if ($metadata->isOptional()) {
                return $metadata->defaultValue();
            }

            throw $throwable;
        }
    }
}
