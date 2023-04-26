<?php

declare(strict_types=1);

use Jrm\RequestBundle\Caster\ArrayCaster;
use Jrm\RequestBundle\Caster\BooleanCaster;
use Jrm\RequestBundle\Caster\DateTimeImmutableCaster;
use Jrm\RequestBundle\Caster\FloatCaster;
use Jrm\RequestBundle\Caster\IntegerCaster;
use Jrm\RequestBundle\Caster\MixedCaster;
use Jrm\RequestBundle\Caster\ObjectCaster;
use Jrm\RequestBundle\Caster\SplFileInfoCaster;
use Jrm\RequestBundle\Caster\StringCaster;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('jrm.request.caster.array_caster', ArrayCaster::class)
            ->args([
                '$casterRegistry' => service('jrm.request.registry.caster_registry'),
                '$itemFactory' => service('jrm.request.factory.item_factory'),
                '$availableTypes' => param('jrm.request.caster.available_types'),
            ])
            ->tag('jrm.request.caster')

        ->set('jrm.request.caster.boolean_caster', BooleanCaster::class)
            ->tag('jrm.request.caster')

        ->set('jrm.request.caster.datetime_immutable_caster', DateTimeImmutableCaster::class)
            ->tag('jrm.request.caster')

        ->set('jrm.request.caster.float_caster', FloatCaster::class)
            ->tag('jrm.request.caster')

        ->set('jrm.request.caster.integer_caster', IntegerCaster::class)
            ->tag('jrm.request.caster')

        ->set('jrm.request.caster.mixed_caster', MixedCaster::class)
            ->tag('jrm.request.caster')

        ->set('jrm.request.caster.mixed_caster', ObjectCaster::class)
            ->args([
                '$itemFactory' => service('jrm.request.factory.item_factory'),
            ])
            ->tag('jrm.request.caster')

        ->set('jrm.request.caster.spl_file_info_caster', SplFileInfoCaster::class)
            ->tag('jrm.request.caster')

        ->set('jrm.request.caster.string_caster', StringCaster::class)
            ->tag('jrm.request.caster');
};
