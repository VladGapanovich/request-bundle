<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Exception;

use Jrm\RequestBundle\Parameter\RequestAttribute;
use LogicException;

final class InvalidRequestParameterException extends LogicException implements RequestBundleException
{
    public function __construct(string $parameterName)
    {
        parent::__construct(sprintf('Parameter %s hasn\'t any %s attributes', $parameterName, RequestAttribute::class));
    }
}
