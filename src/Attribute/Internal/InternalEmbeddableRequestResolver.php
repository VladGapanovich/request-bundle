<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Attribute\Internal;

use Jrm\RequestBundle\Attribute\EmbeddableRequest;
use Jrm\RequestBundle\Exception\InvalidTypeException;
use Jrm\RequestBundle\Exception\UnexpectedAttributeException;
use Jrm\RequestBundle\Exception\UnsupportedTypeException;
use Jrm\RequestBundle\Model\Metadata;
use Jrm\RequestBundle\Service\InternalRequestValuesCollector;
use Jrm\RequestBundle\Service\PropertyAccessor;
use ReflectionNamedType;
use ReflectionType;
use Throwable;

/**
 * @internal
 */
final class InternalEmbeddableRequestResolver implements InternalValueResolver
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
        if (!$attribute instanceof EmbeddableRequest) {
            throw new UnexpectedAttributeException(EmbeddableRequest::class, $attribute::class);
        }

        try {
            $type = $metadata->type();

            if ($this->isEmbeddableRequestClassExists($type)) {
                throw new UnsupportedTypeException($metadata->name()->value(), EmbeddableRequest::class, 'class-string', \get_debug_type($type));
            }

            $path = $attribute->path()?->valueAsArrayKeyPath() ?? $metadata->name()->valueAsArrayKey();
            $value = PropertyAccessor::get($data, $path);

            if (!is_array($value)) {
                throw new InvalidTypeException('array', \get_debug_type($value));
            }

            return $this->internalRequestValuesCollector->collect($type->getName(), $value);
        } catch (Throwable $throwable) {
            if ($metadata->isOptional()) {
                return $metadata->defaultValue();
            }

            throw $throwable;
        }
    }

    // Todo: Move this logic to function
    private function isEmbeddableRequestClassExists(?ReflectionType $type): bool
    {
        return !$type instanceof ReflectionNamedType || !class_exists($type->getName());
    }
}
