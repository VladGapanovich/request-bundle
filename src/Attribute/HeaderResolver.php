<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Attribute;

use Jrm\RequestBundle\Exception\UnexpectedAttributeException;
use Jrm\RequestBundle\Model\Metadata;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;

final class HeaderResolver implements ValueResolver
{
    public function resolve(Request $request, Metadata $metadata, RequestAttribute $attribute): mixed
    {
        if (!$attribute instanceof Header) {
            throw new UnexpectedAttributeException(Header::class, $attribute::class);
        }

        $name = $attribute->name()?->value() ?? $metadata->name()->value();

        /** @var array<int, string|null> $headers */
        $headers = $request->headers->all($name);

        if ($headers === []) {
            if ($metadata->isOptional()) {
                return $metadata->defaultValue();
            }

            throw new RuntimeException(sprintf('Header "%s" is undefined', $name));
        }

        return $headers[0];
    }
}
