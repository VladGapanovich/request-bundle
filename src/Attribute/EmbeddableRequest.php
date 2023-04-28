<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Attribute;

use Attribute;
use Jrm\RequestBundle\Attribute\Internal\InternalEmbeddableRequestResolver;
use Jrm\RequestBundle\Attribute\Internal\InternalRequestAttribute;
use Jrm\RequestBundle\Model\Path;
use Jrm\RequestBundle\Model\Source;

#[Attribute(Attribute::TARGET_PARAMETER | Attribute::TARGET_PROPERTY)]
final class EmbeddableRequest implements RequestAttribute, InternalRequestAttribute
{
    private Source $source;
    private ?Path $path;

    public function __construct(
        string $source = Source::BODY,
        ?string $path = null,
    ) {
        $this->source = new Source($source);
        $this->path = $path !== null ? new Path($path) : null;
    }

    /**
     * @return class-string<EmbeddableRequestResolver>
     */
    public function resolvedBy(): string
    {
        return EmbeddableRequestResolver::class;
    }

    /**
     * @return class-string<InternalEmbeddableRequestResolver>
     */
    public function internalResolvedBy(): string
    {
        return InternalEmbeddableRequestResolver::class;
    }

    public function source(): Source
    {
        return $this->source;
    }

    public function path(): ?Path
    {
        return $this->path;
    }
}
