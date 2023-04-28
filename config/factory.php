<?php

declare(strict_types=1);

use Jrm\RequestBundle\Factory\InvalidTypeConstraintViolationFactory;
use Jrm\RequestBundle\Factory\ItemFactory;
use Jrm\RequestBundle\Factory\MetadataFactory;
use Jrm\RequestBundle\Factory\RequestFactory;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('jrm.request.factory.invalid_type_constraint_violation_factory', InvalidTypeConstraintViolationFactory::class)
            ->args([
                '$translator' => service('translator')->nullOnInvalid(),
            ])

        ->set('jrm.request.factory.item_factory', ItemFactory::class)
            ->args([
                '$internalRequestParameterAttributeResolver' => service('jrm.request.resolver.internal_request_parameter_attribute_resolver'),
                '$itemResolver' => service('jrm.request.parameter.item_resolver'),
                '$metadataFactory' => service(MetadataFactory::class),
            ])

        ->set('jrm.request.factory.metadata_factory', MetadataFactory::class)

        ->set('jrm.request.factory.request_factory', RequestFactory::class)
            ->args([
                '$serializer' => service('serializer'),
                '$invalidTypeConstraintViolationFactory' => service('jrm.request.factory.invalid_type_constraint_violation_factory'),
                '$validator' => service('validator')->nullOnInvalid(),
            ]);
};
