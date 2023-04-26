<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Exception;

use Jrm\RequestBundle\Validator\Violation\Violation;
use RuntimeException;

final class RequestValidationFailedException extends RuntimeException implements RequestBundleException
{
    /**
     * @param Violation[] $violations
     */
    public function __construct(private array $violations)
    {
        parent::__construct('Request validation failed.');
    }

    /**
     * @return Violation[]
     */
    public function violations(): array
    {
        return $this->violations;
    }
}
