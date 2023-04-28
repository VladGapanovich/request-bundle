<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Exception;

use Jrm\RequestBundle\Attribute\RequestAttribute;
use LogicException;

final class InvalidRequestItemException extends LogicException implements RequestBundleException
{
    public function __construct(string $parameterName)
    {
        parent::__construct(sprintf('Parameter|Property %s hasn\'t any %s attributes', $parameterName, RequestAttribute::class));
    }
}
