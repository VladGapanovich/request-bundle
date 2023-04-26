<?php

declare(strict_types=1);

use Jrm\RequestBundle\ArgumentResolver\RequestResolver;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('jrm.request.argument_resolver.request_resolver', RequestResolver::class)
            ->args([
                '$requestFactory' => service('jrm.request.factory.request_factory'),
                '$requestValidator' => service('jrm.request.validator.request_validator'),
            ])
            ->tag('controller.argument_value_resolver', ['priority' => 50]);
};
