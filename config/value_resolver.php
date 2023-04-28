<?php

declare(strict_types=1);

use Jrm\RequestBundle\Attribute\BodyResolver;
use Jrm\RequestBundle\Attribute\CollectionResolver;
use Jrm\RequestBundle\Attribute\CookieResolver;
use Jrm\RequestBundle\Attribute\EmbeddableRequestResolver;
use Jrm\RequestBundle\Attribute\FileResolver;
use Jrm\RequestBundle\Attribute\HeaderResolver;
use Jrm\RequestBundle\Attribute\Internal\InternalItemResolver;
use Jrm\RequestBundle\Attribute\PathAttributeResolver;
use Jrm\RequestBundle\Attribute\QueryResolver;
use Jrm\RequestBundle\DependencyInjection\Compiler\ValueResolverCompilerPass;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('jrm.request.value_resolver.body_resolver', BodyResolver::class)
            ->args([
                '$requestBodyGetter' => service('jrm.request.service.request_body_getter'),
            ])
            ->tag(ValueResolverCompilerPass::VALUE_RESOLVER_TAG)

        ->set('jrm.request.value_resolver.collection_resolver', CollectionResolver::class)
            ->args([
                '$requestBodyGetter' => service('jrm.request.service.request_body_getter'),
                '$internalRequestValuesCollector' => service('jrm.request.service.internal_request_values_collector'),
            ])
            ->tag(ValueResolverCompilerPass::VALUE_RESOLVER_TAG)

        ->set('jrm.request.value_resolver.cookie_resolver', CookieResolver::class)
            ->tag(ValueResolverCompilerPass::VALUE_RESOLVER_TAG)

        ->set('jrm.request.value_resolver.embeddable_request_resolver', EmbeddableRequestResolver::class)
            ->args([
                '$requestBodyGetter' => service('jrm.request.service.request_body_getter'),
                '$internalRequestValuesCollector' => service('jrm.request.service.internal_request_values_collector'),
            ])
            ->tag(ValueResolverCompilerPass::VALUE_RESOLVER_TAG)

        ->set('jrm.request.value_resolver.file_resolver', FileResolver::class)
            ->tag(ValueResolverCompilerPass::VALUE_RESOLVER_TAG)

        ->set('jrm.request.value_resolver.header_resolver', HeaderResolver::class)
            ->tag(ValueResolverCompilerPass::VALUE_RESOLVER_TAG)

        ->set('jrm.request.value_resolver.item_resolver', InternalItemResolver::class)

        ->set('jrm.request.value_resolver.path_attribute_resolver', PathAttributeResolver::class)
            ->tag(ValueResolverCompilerPass::VALUE_RESOLVER_TAG)

        ->set('jrm.request.value_resolver.query_resolver', QueryResolver::class)
            ->tag(ValueResolverCompilerPass::VALUE_RESOLVER_TAG);
};
