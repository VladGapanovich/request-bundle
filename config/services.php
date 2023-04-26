<?php

declare(strict_types=1);

use Jrm\RequestBundle\Service\ParameterResolver;
use Jrm\RequestBundle\Service\ValueToPropertyTypeCaster;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container) {
    $container->import('argument_resolver.php');
    $container->import('caster.php');
    $container->import('factory.php');
    $container->import('listener.php');
    $container->import('parameter_resolver.php');
    $container->import('registry.php');
    $container->import('resolver.php');
    $container->import('serializer.php');
    $container->import('validator.php');

    $container->services()
        ->set('jrm.request.service.parameter_resolver', ParameterResolver::class)
            ->args([
                '$requestParameterAttributeResolver' => service('jrm.request.resolver.request_parameter_attribute_resolver'),
                '$parameterResolverRegistry' => service('jrm.request.registry.parameter_resolver_registry'),
                '$valueToPropertyTypeCaster' => service('jrm.request.service.value_to_property_type_caster'),
            ])

        ->set('jrm.request.service.value_to_property_type_caster', ValueToPropertyTypeCaster::class)
            ->args([
                '$casterRegistry' => service('jrm.request.registry.caster_registry'),
            ]);
};
