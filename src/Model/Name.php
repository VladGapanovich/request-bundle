<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Model;

use InvalidArgumentException;

final readonly class Name
{
    private const INVALID_SYMBOLS_PATTERN = '/[.\[\]]/';

    public function __construct(
        private string $value,
    ) {
        if ($this->value === '') {
            throw new InvalidArgumentException('The name should not be empty.');
        }

        if (preg_match(self::INVALID_SYMBOLS_PATTERN, $this->value, $matches) === 1) {
            throw new InvalidArgumentException(sprintf('Could not parse name "%s". Unexpected token "%s".', $this->value, $matches[0]));
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function valueAsArrayKey(): string
    {
        return sprintf('[%s]', $this->value);
    }
}
