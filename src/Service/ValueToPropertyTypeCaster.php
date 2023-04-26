<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Service;

use Jrm\RequestBundle\Exception\UnsupportedParameterTypeException;
use Jrm\RequestBundle\Model\BaseType;
use Jrm\RequestBundle\Parameter\RequestAttribute;
use Jrm\RequestBundle\Registry\CasterRegistry;
use ReflectionNamedType;
use ReflectionParameter;

final class ValueToPropertyTypeCaster
{
    public function __construct(
        private CasterRegistry $casterRegistry,
    ) {
    }

    public function cast(mixed $value, ReflectionParameter $parameter, RequestAttribute $attribute): mixed
    {
        $type = $parameter->getType();

        if (!$type instanceof ReflectionNamedType && $type !== null) {
            throw new UnsupportedParameterTypeException($type);
        }

        $plainType = $type?->getName() ?? BaseType::MIXED;

        return $this->casterRegistry
            ->getCaster($plainType)
            ->cast($value, $attribute, $plainType, $parameter->allowsNull());
    }
}
