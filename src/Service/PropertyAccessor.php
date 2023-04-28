<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Service;

use Symfony\Component\PropertyAccess\PropertyAccess;

final class PropertyAccessor
{
    /**
     * @param array<array-key, mixed> $data
     */
    public static function get(array $data, string $propertyPath): mixed
    {
        return PropertyAccess::createPropertyAccessorBuilder()
            ->enableExceptionOnInvalidIndex()
            ->getPropertyAccessor()
            ->getValue($data, $propertyPath);
    }
}
