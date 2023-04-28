<?php

declare(strict_types=1);

use Jrm\RequestBundle\Service\InternalItemResolver;
use Jrm\RequestBundle\Service\InternalRequestValuesCollector;
use Jrm\RequestBundle\Service\ItemResolver;
use Jrm\RequestBundle\Service\RequestBodyGetter;
use Jrm\RequestBundle\Service\RequestDataCollector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container) {
    $container->import('internal/value_resolver.php');
    $container->import('argument_resolver.php');
    $container->import('extractor.php');
    $container->import('factory.php');
    $container->import('listener.php');
    $container->import('registry.php');
    $container->import('resolver.php');
    $container->import('serializer.php');
    $container->import('validator.php');
    $container->import('value_resolver.php');

    $container->services()
        ->set('jrm.request.service.internal_item_resolver', InternalItemResolver::class)
            ->args([
                '$internalRequestAttributeResolver' => service('jrm.request.resolver.internal_request_attribute_resolver'),
                '$internalValueResolverRegistry' => service('jrm.request.registry.internal_value_resolver_registry'),
                '$metadataFactory' => service('jrm.request.factory.metadata_factory'),
            ])

        ->set('jrm.request.service.internal_request_values_collector', InternalRequestValuesCollector::class)
            ->args([
                '$internalItemResolver' => service('jrm.request.service.internal_item_resolver'),
            ])

        ->set('jrm.request.service.item_resolver', ItemResolver::class)
            ->args([
                '$requestAttributeResolver' => service('jrm.request.resolver.request_attribute_resolver'),
                '$valueResolverRegistry' => service('jrm.request.registry.value_resolver_registry'),
                '$metadataFactory' => service('jrm.request.factory.metadata_factory'),
            ])

        ->set('jrm.request.service.request_body_getter', RequestBodyGetter::class)
            ->args([
                '$requestFormatValidator' => service('jrm.request.validator.request_format_validator'),
                '$decoder' => service('serializer'),
            ])

        ->set('jrm.request.service.request_data_collector', RequestDataCollector::class)
            ->args([
                '$itemResolver' => service('jrm.request.service.item_resolver'),
            ]);
};
