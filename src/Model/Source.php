<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Model;

use InvalidArgumentException;

final class Source
{
    public const BODY = 'body';
    public const QUERY = 'query';
    public const FORM = 'form';

    public function __construct(private string $value)
    {
        if (!in_array($this->value, [self::BODY, self::QUERY, self::FORM], true)) {
            throw new InvalidArgumentException(sprintf('Source should be body or query, got: %s', $this->value));
        }
    }

    public function value(): string
    {
        return $this->value;
    }
}
