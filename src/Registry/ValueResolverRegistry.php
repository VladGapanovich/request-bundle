<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Registry;

use Jrm\RequestBundle\Attribute\RequestAttribute;
use Jrm\RequestBundle\Attribute\ValueResolver;
use Jrm\RequestBundle\Exception\ValueResolverNotFoundException;

final class ValueResolverRegistry
{
    /**
     * @param array<class-string<ValueResolver>, ValueResolver> $resolvers
     */
    public function __construct(
        private array $resolvers = [],
    ) {
    }

    public function register(ValueResolver $resolver): void
    {
        $this->resolvers[$resolver::class] = $resolver;
    }

    public function resolver(RequestAttribute $attribute): ValueResolver
    {
        $id = $attribute->resolvedBy();

        if (array_key_exists($id, $this->resolvers)) {
            return $this->resolvers[$id];
        }

        throw new ValueResolverNotFoundException($id);
    }
}
