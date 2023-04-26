<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Parameter;

use Jrm\RequestBundle\Exception\InvalidJsonContentException;
use Jrm\RequestBundle\Exception\UnexpectedAttributeException;
use Jrm\RequestBundle\Service\PropertyAccessor;
use JsonException;
use ReflectionParameter;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

final class BodyResolver implements ParameterResolver
{
    public function resolve(
        Request $request,
        ReflectionParameter $parameter,
        RequestAttribute $attribute,
    ): mixed {
        if (!$attribute instanceof Body) {
            throw new UnexpectedAttributeException(Body::class, $attribute::class);
        }

        try {
            /** @var array<string, mixed> $data */
            $data = json_decode((string) $request->getContent(), true, 512, JSON_THROW_ON_ERROR);

            return PropertyAccessor::get($data, $attribute->path()->valueAsArrayKeyPath());
        } catch (JsonException) {
            throw new InvalidJsonContentException($attribute);
        } catch (Throwable $throwable) {
            if ($parameter->isOptional()) {
                return $parameter->getDefaultValue();
            }

            throw $throwable;
        }
    }
}
