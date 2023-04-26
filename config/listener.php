<?php

declare(strict_types=1);

use Jrm\RequestBundle\Listener\RequestValidationFailedExceptionListener;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('jrm.request.listener.request_validation_failed_exception_listener', RequestValidationFailedExceptionListener::class)
            ->args([
                '$serializer' => service('jrm.request.serializer.request_criteria_validation_failed_exception_serializer'),
            ])
            ->tag('kernel.event_listener', ['event' => 'kernel.exception']);
};
