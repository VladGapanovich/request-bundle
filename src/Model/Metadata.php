<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Model;

use ReflectionType;

final readonly class Metadata
{
    public function __construct(
        private Name $name,
        private bool $optional,
        private mixed $defaultValue,
        private ?ReflectionType $type = null,
    ) {
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function isOptional(): bool
    {
        return $this->optional;
    }

    public function defaultValue(): mixed
    {
        return $this->defaultValue;
    }

    public function type(): ?ReflectionType
    {
        return $this->type;
    }
}
