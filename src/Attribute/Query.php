<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Attribute;

use Attribute;
use Jrm\RequestBundle\Model\Path;

#[Attribute(Attribute::TARGET_PARAMETER | Attribute::TARGET_PROPERTY)]
final class Query implements RequestAttribute
{
    private readonly ?Path $path;

    public function __construct(?string $path = null)
    {
        $this->path = $path !== null ? new Path($path) : null;
    }

    /**
     * @return class-string<QueryResolver>
     */
    public function resolvedBy(): string
    {
        return QueryResolver::class;
    }

    public function path(): ?Path
    {
        return $this->path;
    }
}
