<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Exception;

use Jrm\RequestBundle\Parameter\InternalRequestAttribute;
use LogicException;

final class InvalidInternalRequestParameterException extends LogicException implements RequestBundleException
{
    public function __construct(string $parameterName)
    {
        parent::__construct(sprintf('Parameter %s hasn\'t any %s attributes', $parameterName, InternalRequestAttribute::class));
    }
}
