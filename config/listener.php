<?php

declare(strict_types=1);

use Jrm\RequestBundle\Listener\RequestValidationFailedExceptionListener;
use Jrm\RequestBundle\Serializer\RequestValidationFailedExceptionSerializer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set(RequestValidationFailedExceptionListener::class)
            ->args([
                '$serializer' => service(RequestValidationFailedExceptionSerializer::class),
            ])
            ->tag('kernel.event_listener', ['event' => 'kernel.exception']);
};
