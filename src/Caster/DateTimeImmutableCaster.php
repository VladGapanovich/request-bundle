<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Caster;

use DateTimeImmutable;
use Jrm\RequestBundle\Exception\InvalidTypeException;
use Jrm\RequestBundle\Parameter\RequestAttribute;
use Throwable;

final class DateTimeImmutableCaster implements Caster
{
    public static function getType(): string
    {
        return DateTimeImmutable::class;
    }

    public function cast(mixed $value, RequestAttribute $attribute, string $type, bool $allowsNull): ?DateTimeImmutable
    {
        if ($value === null && $allowsNull) {
            return null;
        }

        if (!is_scalar($value)) {
            throw new InvalidTypeException(DateTimeImmutable::class, get_debug_type($value));
        }

        try {
            return new DateTimeImmutable((string) $value);
        } catch (Throwable) {
            throw new InvalidTypeException(DateTimeImmutable::class, get_debug_type($value));
        }
    }
}
