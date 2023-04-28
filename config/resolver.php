<?php

declare(strict_types=1);

use Jrm\RequestBundle\Resolver\InternalRequestAttributeResolver;
use Jrm\RequestBundle\Resolver\RequestAttributeResolver;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('jrm.request.resolver.internal_request_attribute_resolver', InternalRequestAttributeResolver::class)

        ->set('jrm.request.resolver.request_attribute_resolver', RequestAttributeResolver::class);
};
