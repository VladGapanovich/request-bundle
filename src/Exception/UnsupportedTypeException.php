<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Exception;

use LogicException;

final class UnsupportedTypeException extends LogicException implements RequestBundleException
{
    /**
     * @param class-string $attributeClassName
     */
    public function __construct(string $itemName, string $attributeClassName, string $expected, string $actual)
    {
        parent::__construct(sprintf(
            'Request parameter/property %s with %s should have type %s, got %s',
            $itemName,
            $attributeClassName,
            $expected,
            $actual,
        ));
    }
}
