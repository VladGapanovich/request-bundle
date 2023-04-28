<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Exception;

use InvalidArgumentException;

final class InvalidTypeException extends InvalidArgumentException implements RequestBundleException
{
    public function __construct(string $expected, string $actual)
    {
        parent::__construct(sprintf('Invalid argument type. Expected %s, got %s.', $expected, $actual));
    }
}
