<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class InternalValueResolverCompilerPass implements CompilerPassInterface
{
    public const INTERNAL_VALUE_RESOLVER_TAG = 'jrm.request.internal_value_resolver';

    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('jrm.request.registry.internal_value_resolver_registry')) {
            return;
        }

        $definition = $container->getDefinition('jrm.request.registry.internal_value_resolver_registry');

        foreach (array_keys($container->findTaggedServiceIds(self::INTERNAL_VALUE_RESOLVER_TAG, true)) as $id) {
            $definition->addMethodCall('register', [new Reference($id)]);
        }
    }
}
