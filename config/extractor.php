<?php

declare(strict_types=1);

use Jrm\RequestBundle\Extractor\CollectionReflectionExtractor;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('jrm.request.extractor.collection_reflection_extractor', CollectionReflectionExtractor::class)
            ->args([
                '$propertyTypeExtractor' => service('property_info.reflection_extractor'),
            ])
            ->tag('property_info.type_extractor', ['priority' => -999]);
};
