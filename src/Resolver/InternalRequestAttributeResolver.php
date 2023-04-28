<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Resolver;

use Jrm\RequestBundle\Attribute\Internal\InternalRequestAttribute;
use Jrm\RequestBundle\Exception\InvalidInternalRequestItemException;
use ReflectionAttribute;
use ReflectionParameter;
use ReflectionProperty;

/**
 * @internal
 */
final class InternalRequestAttributeResolver
{
    public function resolve(ReflectionParameter|ReflectionProperty $item): InternalRequestAttribute
    {
        $attribute = $item->getAttributes(InternalRequestAttribute::class, ReflectionAttribute::IS_INSTANCEOF)[0]
            ?? throw new InvalidInternalRequestItemException($item->getName());

        return $attribute->newInstance();
    }
}
