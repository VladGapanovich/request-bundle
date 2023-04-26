<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Caster;

use Jrm\RequestBundle\Exception\InvalidTypeException;
use Jrm\RequestBundle\Model\BaseType;
use Jrm\RequestBundle\Parameter\RequestAttribute;

final class FloatCaster implements Caster
{
    public static function getType(): string
    {
        return BaseType::FLOAT;
    }

    public function cast(mixed $value, RequestAttribute $attribute, string $type, bool $allowsNull): ?float
    {
        if ($value === null && $allowsNull) {
            return null;
        }

        $value = filter_var($value, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);

        if (is_float($value)) {
            return $value;
        }

        throw new InvalidTypeException(BaseType::FLOAT, get_debug_type($value));
    }
}
