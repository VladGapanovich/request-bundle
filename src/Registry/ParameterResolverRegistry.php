<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Registry;

use Jrm\RequestBundle\Exception\ParameterResolverNotFoundException;
use Jrm\RequestBundle\Parameter\RequestAttribute;
use Jrm\RequestBundle\Parameter\ParameterResolver;

final class ParameterResolverRegistry
{
    /**
     * @param array<class-string<ParameterResolver>, ParameterResolver> $resolvers
     */
    public function __construct(
        private array $resolvers = [],
    ) {
    }

    public function register(ParameterResolver $resolver): void
    {
        $this->resolvers[$resolver::class] = $resolver;
    }

    public function resolver(RequestAttribute $attribute): ParameterResolver
    {
        $id = $attribute->resolvedBy();

        if (array_key_exists($id, $this->resolvers)) {
            return $this->resolvers[$id];
        }

        throw new ParameterResolverNotFoundException($id);
    }
}
