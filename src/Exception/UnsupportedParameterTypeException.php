<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Exception;

use LogicException;
use ReflectionType;

final class UnsupportedParameterTypeException extends LogicException implements RequestBundleException
{
    public function __construct(ReflectionType $type)
    {
        parent::__construct(sprintf('Type %s is unsupported', $type::class));
    }
}
