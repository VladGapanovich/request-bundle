<?php

declare(strict_types=1);

use Jrm\RequestBundle\Factory\ItemFactory;
use Jrm\RequestBundle\Factory\RequestFactory;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('jrm.request.factory.item_factory', ItemFactory::class)
            ->args([
                '$internalRequestParameterAttributeResolver' => service('jrm.request.resolver.internal_request_parameter_attribute_resolver'),
                '$itemResolver' => service('jrm.request.parameter.item_resolver'),
                '$valueToPropertyTypeCaster' => service('jrm.request.service.value_to_property_type_caster'),
            ])

        ->set('jrm.request.factory.request_factory', RequestFactory::class)
            ->args([
                '$parameterResolver' => service('jrm.request.service.parameter_resolver'),
            ]);
};
