<?php

declare(strict_types=1);

namespace Jrm\RequestBundle;

use Jrm\RequestBundle\DependencyInjection\Compiler\InternalValueResolverCompilerPass;
use Jrm\RequestBundle\DependencyInjection\Compiler\ValueResolverCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class JrmRequestBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new InternalValueResolverCompilerPass());
        $container->addCompilerPass(new ValueResolverCompilerPass());
    }

    public function getPath(): string
    {
        return dirname(__DIR__);
    }
}
