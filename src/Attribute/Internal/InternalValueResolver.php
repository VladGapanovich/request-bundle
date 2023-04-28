<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Attribute\Internal;

use Jrm\RequestBundle\Model\Metadata;

/**
 * @internal
 */
interface InternalValueResolver
{
    /**
     * @param array<string, mixed> $data
     */
    public function resolve(array $data, Metadata $metadata, InternalRequestAttribute $attribute): mixed;
}
