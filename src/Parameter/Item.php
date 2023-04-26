<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Parameter;

use Attribute;
use Jrm\RequestBundle\Model\Path;

#[Attribute(Attribute::TARGET_PARAMETER | Attribute::TARGET_PROPERTY)]
final class Item implements InternalRequestAttribute
{
    private Path $path;

    public function __construct(string $path)
    {
        $this->path = new Path($path);
    }

    public function path(): Path
    {
        return $this->path;
    }

    /**
     * @return class-string<ItemResolver>
     */
    public function resolvedBy(): string
    {
        return ItemResolver::class;
    }
}
