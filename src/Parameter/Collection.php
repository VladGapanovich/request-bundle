<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Parameter;

use Attribute;
use Jrm\RequestBundle\Model\Path;
use Jrm\RequestBundle\Model\Source;

#[Attribute(Attribute::TARGET_PARAMETER | Attribute::TARGET_PROPERTY)]
final class Collection implements InternalRequestAttribute, RequestAttribute
{
    private Path $path;
    private Source $source;

    public function __construct(
        private string $type,
        string $path,
        string $source = Source::BODY,
    ) {
        $this->path = new Path($path);
        $this->source = new Source($source);
    }

    public function type(): string
    {
        return $this->type;
    }

    public function path(): Path
    {
        return $this->path;
    }

    public function source(): Source
    {
        return $this->source;
    }

    /**
     * @return class-string<CollectionResolver>
     */
    public function resolvedBy(): string
    {
        return CollectionResolver::class;
    }
}
