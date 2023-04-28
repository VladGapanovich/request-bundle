<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Attribute;

interface RequestAttribute
{
    /**
     * @return class-string<ValueResolver>
     */
    public function resolvedBy(): string;
}
