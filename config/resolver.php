<?php

declare(strict_types=1);

use Jrm\RequestBundle\Resolver\InternalRequestParameterAttributeResolver;
use Jrm\RequestBundle\Resolver\RequestParameterAttributeResolver;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('jrm.request.resolver.internal_request_parameter_attribute_resolver', InternalRequestParameterAttributeResolver::class)

        ->set('jrm.request.resolver.request_parameter_attribute_resolver', RequestParameterAttributeResolver::class);
};
