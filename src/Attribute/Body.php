<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Attribute;

use Attribute;
use Jrm\RequestBundle\Model\Path;

#[Attribute(Attribute::TARGET_PARAMETER | Attribute::TARGET_PROPERTY)]
final class Body implements RequestAttribute
{
    private ?Path $path;

    public function __construct(?string $path = null)
    {
        $this->path = $path !== null ? new Path($path) : null;
    }

    /**
     * @return class-string<BodyResolver>
     */
    public function resolvedBy(): string
    {
        return BodyResolver::class;
    }

    public function path(): ?Path
    {
        return $this->path;
    }
}
