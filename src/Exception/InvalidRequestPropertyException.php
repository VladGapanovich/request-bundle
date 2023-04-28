<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Exception;

use Jrm\RequestBundle\Attribute\RequestAttribute;
use LogicException;

final class InvalidRequestPropertyException extends LogicException implements RequestBundleException
{
    public function __construct(string $propertyName)
    {
        parent::__construct(sprintf('Property %s hasn\'t any %s attributes', $propertyName, RequestAttribute::class));
    }
}
