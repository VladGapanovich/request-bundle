<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class CasterCompilerPass implements CompilerPassInterface
{
    public const CASTER_TAG = 'jrm.request.caster';

    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('jrm.request.registry.caster_registry')) {
            return;
        }

        $definition = $container->getDefinition('jrm.request.registry.caster_registry');

        foreach ($container->findTaggedServiceIds(self::CASTER_TAG, true) as $id => $attributes) {
            $definition->addMethodCall('register', [new Reference($id)]);
        }
    }
}
