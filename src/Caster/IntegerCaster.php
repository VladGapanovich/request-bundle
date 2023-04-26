<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Caster;

use Jrm\RequestBundle\Exception\InvalidTypeException;
use Jrm\RequestBundle\Model\BaseType;
use Jrm\RequestBundle\Parameter\RequestAttribute;

final class IntegerCaster implements Caster
{
    public static function getType(): string
    {
        return BaseType::INT;
    }

    public function cast(mixed $value, RequestAttribute $attribute, string $type, bool $allowsNull): ?int
    {
        if ($value === null && $allowsNull) {
            return null;
        }

        $value = filter_var($value, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);

        if (is_int($value)) {
            return $value;
        }

        throw new InvalidTypeException(BaseType::INT, get_debug_type($value));
    }
}
