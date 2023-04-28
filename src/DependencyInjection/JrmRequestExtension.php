<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\DependencyInjection;

use Jrm\RequestBundle\Attribute\Internal\InternalValueResolver;
use Jrm\RequestBundle\Attribute\ValueResolver;
use Jrm\RequestBundle\DependencyInjection\Compiler\InternalValueResolverCompilerPass;
use Jrm\RequestBundle\DependencyInjection\Compiler\ValueResolverCompilerPass;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class JrmRequestExtension extends Extension
{
    /**
     * @param array<string, mixed> $configs
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../../config'),
        );
        $loader->load('services.php');

        $container->registerForAutoconfiguration(InternalValueResolver::class)
            ->addTag(InternalValueResolverCompilerPass::INTERNAL_VALUE_RESOLVER_TAG);
        $container->registerForAutoconfiguration(ValueResolver::class)
            ->addTag(ValueResolverCompilerPass::VALUE_RESOLVER_TAG);
    }
}
