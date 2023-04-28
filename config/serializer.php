<?php

declare(strict_types=1);

use Jrm\RequestBundle\Serializer\RequestValidationFailedExceptionSerializer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set(RequestValidationFailedExceptionSerializer::class);
};
