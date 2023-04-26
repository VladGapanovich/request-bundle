<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Caster;

use Jrm\RequestBundle\Exception\InvalidTypeException;
use Jrm\RequestBundle\Model\BaseType;
use Jrm\RequestBundle\Parameter\RequestAttribute;

final class StringCaster implements Caster
{
    public static function getType(): string
    {
        return BaseType::STRING;
    }

    public function cast(mixed $value, RequestAttribute $attribute, string $type, bool $allowsNull): ?string
    {
        if ($value === null && $allowsNull) {
            return null;
        }

        if (is_scalar($value)) {
            return (string) $value;
        }

        throw new InvalidTypeException(BaseType::STRING, get_debug_type($value));
    }
}
