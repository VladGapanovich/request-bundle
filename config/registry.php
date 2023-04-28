<?php

declare(strict_types=1);

use Jrm\RequestBundle\Registry\InternalValueResolverRegistry;
use Jrm\RequestBundle\Registry\ValueResolverRegistry;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('jrm.request.registry.internal_value_resolver_registry', InternalValueResolverRegistry::class)

        ->set('jrm.request.registry.value_resolver_registry', ValueResolverRegistry::class);
};
