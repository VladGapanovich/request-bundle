<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Factory;

use Jrm\RequestBundle\Model\Metadata;
use Jrm\RequestBundle\Model\Name;
use ReflectionParameter;
use ReflectionProperty;

final class MetadataFactory
{
    public function createByParameter(ReflectionParameter $parameter): Metadata
    {
        return new Metadata(
            new Name($parameter->getName()),
            $parameter->isOptional(),
            $parameter->isOptional() ? $parameter->getDefaultValue() : null,
            $parameter->getType(),
        );
    }

    public function createByProperty(ReflectionProperty $parameter): Metadata
    {
        return new Metadata(
            new Name($parameter->getName()),
            $parameter->hasDefaultValue(),
            $parameter->hasDefaultValue() ? $parameter->getDefaultValue() : null,
            $parameter->getType(),
        );
    }
}
