<?php

declare(strict_types=1);

use Jrm\RequestBundle\Parameter\BodyResolver;
use Jrm\RequestBundle\Parameter\CollectionResolver;
use Jrm\RequestBundle\Parameter\CookieResolver;
use Jrm\RequestBundle\Parameter\EmbeddableRequestResolver;
use Jrm\RequestBundle\Parameter\FileResolver;
use Jrm\RequestBundle\Parameter\FormResolver;
use Jrm\RequestBundle\Parameter\HeaderResolver;
use Jrm\RequestBundle\Parameter\ItemResolver;
use Jrm\RequestBundle\Parameter\PathAttributeResolver;
use Jrm\RequestBundle\Parameter\QueryResolver;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('jrm.request.parameter.body_resolver', BodyResolver::class)
            ->tag('jrm.request.parameter_resolver')

        ->set('jrm.request.parameter.collection_resolver', CollectionResolver::class)
            ->tag('jrm.request.parameter_resolver')

        ->set('jrm.request.parameter.cookie_resolver', CookieResolver::class)
            ->tag('jrm.request.parameter_resolver')

        ->set('jrm.request.parameter.embeddable_request_resolver', EmbeddableRequestResolver::class)
            ->tag('jrm.request.parameter_resolver')

        ->set('jrm.request.parameter.file_resolver', FileResolver::class)
            ->tag('jrm.request.parameter_resolver')

        ->set('jrm.request.parameter.form_resolver', FormResolver::class)
            ->tag('jrm.request.parameter_resolver')

        ->set('jrm.request.parameter.header_resolver', HeaderResolver::class)
            ->tag('jrm.request.parameter_resolver')

        ->set('jrm.request.parameter.item_resolver', ItemResolver::class)

        ->set('jrm.request.parameter.path_attribute_resolver', PathAttributeResolver::class)
            ->tag('jrm.request.parameter_resolver')

        ->set('jrm.request.parameter.query_resolver', QueryResolver::class)
            ->tag('jrm.request.parameter_resolver');
};
