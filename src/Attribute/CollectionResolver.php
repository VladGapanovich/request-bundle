<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Attribute;

use Jrm\RequestBundle\Exception\InvalidTypeException;
use Jrm\RequestBundle\Exception\UnexpectedAttributeException;
use Jrm\RequestBundle\Model\Metadata;
use Jrm\RequestBundle\Model\Source;
use Jrm\RequestBundle\Service\InternalRequestValuesCollector;
use Jrm\RequestBundle\Service\PropertyAccessor;
use Jrm\RequestBundle\Service\RequestBodyGetter;
use LogicException;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

final readonly class CollectionResolver implements ValueResolver
{
    public function __construct(
        private RequestBodyGetter $requestBodyGetter,
        private InternalRequestValuesCollector $internalRequestValuesCollector,
    ) {
    }

    public function resolve(Request $request, Metadata $metadata, RequestAttribute $attribute): mixed
    {
        if (!$attribute instanceof Collection) {
            throw new UnexpectedAttributeException(Collection::class, $attribute::class);
        }

        $data = match ($attribute->source()->value()) {
            Source::BODY => $this->requestBodyGetter->get($request),
            Source::QUERY => $request->query->all(),
            default => throw new LogicException(sprintf('Undefined source %s', $attribute->source()->value())),
        };

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
