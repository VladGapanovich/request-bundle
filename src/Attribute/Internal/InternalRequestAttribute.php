<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Attribute\Internal;

/**
 * @internal
 */
interface InternalRequestAttribute
{
    /**
     * @return class-string<InternalValueResolver>
     */
    public function internalResolvedBy(): string;
}
