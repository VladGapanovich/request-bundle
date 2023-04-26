<?php

declare(strict_types=1);

use Jrm\RequestBundle\Registry\CasterRegistry;
use Jrm\RequestBundle\Registry\ParameterResolverRegistry;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('jrm.request.registry.caster_registry', CasterRegistry::class)

        ->set('jrm.request.registry.parameter_resolver_registry', ParameterResolverRegistry::class);
};
