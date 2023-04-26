<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Parameter;

use Jrm\RequestBundle\Exception\UnexpectedAttributeException;
use ReflectionParameter;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;

final class HeaderResolver implements ParameterResolver
{
    public function resolve(
        Request $request,
        ReflectionParameter $parameter,
        RequestAttribute $attribute,
    ): mixed {
        if (!$attribute instanceof Header) {
            throw new UnexpectedAttributeException(Header::class, $attribute::class);
        }

        /** @var array<int, string|null> $headers */
        $headers = $request->headers->all($attribute->name()->value());

        if (count($headers) === 0) {
            if ($parameter->isOptional()) {
                return $parameter->getDefaultValue();
            }

            throw new RuntimeException(sprintf('Header "%s" is undefined', $attribute->name()->value()));
        }

        return $headers[0] === null ? null : (string) $headers[0];
    }
}
