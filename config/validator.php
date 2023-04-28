<?php

declare(strict_types=1);

use Jrm\RequestBundle\Validator\RequestFormatValidator;
use Jrm\RequestBundle\Validator\Violation\Factory\ViolationFactory;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('jrm.request.validator.violation_factory', ViolationFactory::class)

        ->set('jrm.request.validator.request_format_validator', RequestFormatValidator::class);
};
