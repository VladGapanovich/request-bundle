<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class ParameterResolverCompilerPass implements CompilerPassInterface
{
    public const PARAMETER_RESOLVER_TAG = 'jrm.request.parameter_resolver';

    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('jrm.request.registry.parameter_resolver_registry')) {
            return;
        }

        $definition = $container->getDefinition('jrm.request.registry.parameter_resolver_registry');

        foreach ($container->findTaggedServiceIds(self::PARAMETER_RESOLVER_TAG, true) as $id => $attributes) {
            $definition->addMethodCall('register', [new Reference($id)]);
        }
    }
}
