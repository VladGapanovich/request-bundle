<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Parameter;

interface RequestAttribute
{
    /**
     * @return class-string<ParameterResolver>
     */
    public function resolvedBy(): string;
}
