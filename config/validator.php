<?php

declare(strict_types=1);

use Jrm\RequestBundle\Validator\RequestValidator;
use Jrm\RequestBundle\Validator\Violation\Factory\ViolationFactory;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('jrm.request.validator.violation_factory', ViolationFactory::class)

        ->set('jrm.request.validator.request_validator', RequestValidator::class)
        ->args([
            '$validator' => service('validator'),
            '$violationFactory' => service('jrm.request.validator.violation_factory'),
        ]);
};
