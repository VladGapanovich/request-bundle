<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Registry;

use Jrm\RequestBundle\Attribute\Internal\InternalRequestAttribute;
use Jrm\RequestBundle\Attribute\Internal\InternalValueResolver;
use Jrm\RequestBundle\Exception\InternalValueResolverNotFoundException;

final class InternalValueResolverRegistry
{
    /**
     * @template T of InternalValueResolver
     *
     * @param array<class-string<T>, T> $resolvers
     */
    public function __construct(
        private array $resolvers = [],
    ) {
    }

    public function register(InternalValueResolver $resolver): void
    {
        $this->resolvers[$resolver::class] = $resolver;
    }

    public function resolver(InternalRequestAttribute $attribute): InternalValueResolver
    {
        $id = $attribute->internalResolvedBy();

        if (array_key_exists($id, $this->resolvers)) {
            return $this->resolvers[$id];
        }

        throw new InternalValueResolverNotFoundException($id);
    }
}
