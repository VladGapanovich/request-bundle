<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Attribute;

use Jrm\RequestBundle\Exception\UnexpectedAttributeException;
use Jrm\RequestBundle\Model\Metadata;
use Jrm\RequestBundle\Service\PropertyAccessor;
use Jrm\RequestBundle\Service\RequestBodyGetter;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

final readonly class BodyResolver implements ValueResolver
{
    public function __construct(
        private RequestBodyGetter $requestBodyGetter,
    ) {
    }

    public function resolve(Request $request, Metadata $metadata, RequestAttribute $attribute): mixed
    {
        if (!$attribute instanceof Body) {
            throw new UnexpectedAttributeException(Body::class, $attribute::class);
        }

        $data = $this->requestBodyGetter->get($request);

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
