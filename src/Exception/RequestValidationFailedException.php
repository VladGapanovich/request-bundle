<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Exception;

use RuntimeException;
use Symfony\Component\Validator\ConstraintViolationList;
use Throwable;

final class RequestValidationFailedException extends RuntimeException implements RequestBundleException
{
    public function __construct(
        private readonly int $statusCode,
        string $message,
        private readonly ?ConstraintViolationList $violations = null,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }

    public function statusCode(): int
    {
        return $this->statusCode;
    }

    public function violations(): ?ConstraintViolationList
    {
        return $this->violations;
    }
}
