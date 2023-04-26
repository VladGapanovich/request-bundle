<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Caster;

use Jrm\RequestBundle\Exception\InvalidTypeException;
use Jrm\RequestBundle\Parameter\RequestAttribute;
use SplFileInfo;

final class SplFileInfoCaster implements Caster
{
    public static function getType(): string
    {
        return SplFileInfo::class;
    }

    public function cast(mixed $value, RequestAttribute $attribute, string $type, bool $allowsNull): ?SplFileInfo
    {
        if ($value === null && $allowsNull) {
            return null;
        }

        if ($value instanceof SplFileInfo) {
            return $value;
        }

        throw new InvalidTypeException(SplFileInfo::class, get_debug_type($value));
    }
}
