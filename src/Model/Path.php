<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Model;

use InvalidArgumentException;

final readonly class Path
{
    private const PATTERN = '/^(?<firstDot>\.)|(?<squareBrackets>[\[\]])|(?<latDot>\.)$/';

    public function __construct(
        private string $value,
    ) {
        if ($this->value === '') {
            throw new InvalidArgumentException('The name should not be empty.');
        }

        if (preg_match(self::PATTERN, $this->value, $matches) === 1) {
            $error = match (true) {
                isset($matches['latDot']) => 'The path does not have to end with a dot.',
                isset($matches['squareBrackets']) => sprintf('Path should not contain square bracket at position %d.', strpos($this->value, $matches['squareBrackets'])),
                isset($matches['firstDot']) => 'The path does not have to start with a dot.',
            };

            throw new InvalidArgumentException(sprintf('Could not parse name "%s". %s', $this->value, $error));
        }
    }

    public function valueAsArrayKeyPath(): string
    {
        return implode(
            '',
            array_map(
                static fn (string $name): string => sprintf('[%s]', $name),
                explode('.', $this->value),
            ),
        );
    }

    public function value(): string
    {
        return $this->value;
    }
}
