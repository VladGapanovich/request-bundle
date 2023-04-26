<?php

declare(strict_types=1);

use Jrm\RequestBundle\Serializer\RequestValidationFailedExceptionSerializer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('jrm.request.serializer.request_criteria_validation_failed_exception_serializer', RequestValidationFailedExceptionSerializer::class);
};
