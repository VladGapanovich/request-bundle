<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Caster;

use Jrm\RequestBundle\Exception\InvalidTypeException;
use Jrm\RequestBundle\Model\BaseType;
use Jrm\RequestBundle\Parameter\RequestAttribute;

final class BooleanCaster implements Caster
{
    public static function getType(): string
    {
        return BaseType::BOOL;
    }

    public function cast(mixed $value, RequestAttribute $attribute, string $type, bool $allowsNull): ?bool
    {
        if ($value === null) {
            return $allowsNull ? null : throw new InvalidTypeException(BaseType::BOOL, 'null');
        }

        $value = filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);

        if (is_bool($value)) {
            return $value;
        }

        throw new InvalidTypeException(BaseType::BOOL, get_debug_type($value));
    }
}
