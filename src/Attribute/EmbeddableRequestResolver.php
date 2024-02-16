<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Attribute;

use Jrm\RequestBundle\Exception\InvalidTypeException;
use Jrm\RequestBundle\Exception\UnexpectedAttributeException;
use Jrm\RequestBundle\Exception\UnsupportedTypeException;
use Jrm\RequestBundle\Model\Metadata;
use Jrm\RequestBundle\Model\Source;
use Jrm\RequestBundle\Service\InternalRequestValuesCollector;
use Jrm\RequestBundle\Service\PropertyAccessor;
use Jrm\RequestBundle\Service\RequestBodyGetter;
use LogicException;
use ReflectionNamedType;
use ReflectionType;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

final readonly class EmbeddableRequestResolver implements ValueResolver
{
    public function __construct(
        private RequestBodyGetter $requestBodyGetter,
        private InternalRequestValuesCollector $internalRequestValuesCollector,
    ) {
    }

    public function resolve(Request $request, Metadata $metadata, RequestAttribute $attribute): mixed
    {
        if (!$attribute instanceof EmbeddableRequest) {
            throw new UnexpectedAttributeException(EmbeddableRequest::class, $attribute::class);
        }

        $data = match ($attribute->source()->value()) {
            Source::BODY => $this->requestBodyGetter->get($request),
            Source::QUERY => $request->query->all(),
            default => throw new LogicException(sprintf('Undefined source %s', $attribute->source()->value())),
        };

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
