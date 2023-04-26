<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Exception;

use RuntimeException;

final class InvalidTypeException extends RuntimeException implements RequestBundleException
{
    public function __construct(string $expected, string $actual)
    {
        parent::__construct(sprintf('Invalid type. Expected %s, got %s.', $expected, $actual));
    }
}
