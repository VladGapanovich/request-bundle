<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Parameter;

use Jrm\RequestBundle\Exception\UnexpectedAttributeException;
use Jrm\RequestBundle\Model\Source;
use Jrm\RequestBundle\Service\PropertyAccessor;
use LogicException;
use ReflectionParameter;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Throwable;

final class EmbeddableRequestResolver implements ParameterResolver
{
    public function resolve(
        SymfonyRequest $request,
        ReflectionParameter $parameter,
        RequestAttribute $attribute,
    ): mixed {
        if (!$attribute instanceof EmbeddableRequest) {
            throw new UnexpectedAttributeException(EmbeddableRequest::class, $attribute::class);
        }

        try {
            $source = match ($attribute->source()->value()) {
                Source::BODY => json_decode((string) $request->getContent(), true, 512, JSON_THROW_ON_ERROR),
                Source::QUERY => $request->query->all(),
                Source::FORM => $request->request->all(),
                default => throw new LogicException(sprintf('Undefined source %s', $attribute->source()->value())),
            };

            return PropertyAccessor::get((array) $source, $attribute->path()->valueAsArrayKeyPath());
        } catch (Throwable $throwable) {
            if ($parameter->isOptional()) {
                return $parameter->getDefaultValue();
            }

            throw $throwable;
        }
    }
}
