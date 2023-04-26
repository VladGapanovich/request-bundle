<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Exception;

use LogicException;

final class UnexpectedAttributeException extends LogicException implements RequestBundleException
{
    public function __construct(string $expected, string $actual)
    {
        parent::__construct(sprintf(
            'Unexpected annotation. Expected %s, got %s.',
            $expected,
            $actual,
        ));
    }
}
