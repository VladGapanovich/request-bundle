<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Attribute;

use Attribute;
use Jrm\RequestBundle\Attribute\Internal\InternalCollectionResolver;
use Jrm\RequestBundle\Attribute\Internal\InternalRequestAttribute;
use Jrm\RequestBundle\Model\Path;
use Jrm\RequestBundle\Model\Source;
use LogicException;

#[Attribute(Attribute::TARGET_PARAMETER | Attribute::TARGET_PROPERTY)]
final class Collection implements RequestAttribute, InternalRequestAttribute
{
    private Source $source;
    private ?Path $path;

    /**
     * @param class-string $type
     */
    public function __construct(
        private string $type,
        string $source = Source::BODY,
        ?string $path = null,
    ) {
        if (!class_exists($this->type)) {
            throw new LogicException('Collection should have class type');
        }

        $this->source = new Source($source);
        $this->path = $path !== null ? new Path($path) : null;
    }

    /**
     * @return class-string<CollectionResolver>
     */
    public function resolvedBy(): string
    {
        return CollectionResolver::class;
    }

    /**
     * @return class-string<InternalCollectionResolver>
     */
    public function internalResolvedBy(): string
    {
        return InternalCollectionResolver::class;
    }

    /**
     * @return class-string
     */
    public function type(): string
    {
        return $this->type;
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
