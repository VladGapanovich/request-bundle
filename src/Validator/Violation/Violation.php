<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Validator\Violation;

use Stringable;

final class Violation
{
    /**
     * @param array<string, mixed> $parameters
     */
    public function __construct(
        private string|Stringable $message,
        private array $parameters,
        private ?string $code,
        private ?string $propertyPath,
    ) {
    }

    public function message(): string
    {
        return (string) $this->message;
    }

    /**
     * @return array<string, mixed>
     */
    public function parameters(): array
    {
        return $this->parameters;
    }

    public function code(): ?string
    {
        return $this->code;
    }

    public function propertyPath(): ?string
    {
        return $this->propertyPath;
    }
}
