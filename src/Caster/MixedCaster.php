<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Caster;

use Jrm\RequestBundle\Model\BaseType;
use Jrm\RequestBundle\Parameter\RequestAttribute;

final class MixedCaster implements Caster
{
    public static function getType(): string
    {
        return BaseType::MIXED;
    }

    public function cast(mixed $value, RequestAttribute $attribute, string $type, bool $allowsNull): mixed
    {
        return $value;
    }
}
