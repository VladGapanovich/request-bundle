<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Exception;

use Jrm\RequestBundle\Attribute\Internal\InternalRequestAttribute;
use LogicException;

final class InvalidInternalRequestItemException extends LogicException implements RequestBundleException
{
    public function __construct(string $name)
    {
        parent::__construct(sprintf('Parameter|property %s hasn\'t any %s attributes', $name, InternalRequestAttribute::class));
    }
}
