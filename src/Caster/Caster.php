<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Caster;

use Jrm\RequestBundle\Parameter\RequestAttribute;

interface Caster
{
    public static function getType(): string;

    public function cast(mixed $value, RequestAttribute $attribute, string $type, bool $allowsNull): mixed;
}
