<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class ValueResolverCompilerPass implements CompilerPassInterface
{
    public const VALUE_RESOLVER_TAG = 'jrm.request.value_resolver';

    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('jrm.request.registry.value_resolver_registry')) {
            return;
        }

        $definition = $container->getDefinition('jrm.request.registry.value_resolver_registry');

        foreach (array_keys($container->findTaggedServiceIds(self::VALUE_RESOLVER_TAG, true)) as $id) {
            $definition->addMethodCall('register', [new Reference($id)]);
        }
    }
}
