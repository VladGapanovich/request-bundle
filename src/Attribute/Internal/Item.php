<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Attribute\Internal;

use Attribute;
use Jrm\RequestBundle\Model\Path;

#[Attribute(Attribute::TARGET_PARAMETER | Attribute::TARGET_PROPERTY)]
final class Item implements InternalRequestAttribute
{
    private ?Path $path;

    public function __construct(?string $path = null)
    {
        $this->path = $path !== null ? new Path($path) : null;
    }

    /**
     * @return class-string<InternalItemResolver>
     */
    public function internalResolvedBy(): string
    {
        return InternalItemResolver::class;
    }

    public function path(): ?Path
    {
        return $this->path;
    }
}
