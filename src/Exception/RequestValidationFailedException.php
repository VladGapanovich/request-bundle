<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Exception;

use RuntimeException;
use Symfony\Component\Validator\ConstraintViolationList;

final class RequestValidationFailedException extends RuntimeException implements RequestBundleException
{
    public function __construct(
        private int $statusCode,
        string $message,
        private ?ConstraintViolationList $violations = null,
        ?\Throwable $previous = null,
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
