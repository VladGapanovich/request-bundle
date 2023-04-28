<?php

declare(strict_types=1);

use Jrm\RequestBundle\Attribute\Internal\InternalCollectionResolver;
use Jrm\RequestBundle\Attribute\Internal\InternalEmbeddableRequestResolver;
use Jrm\RequestBundle\Attribute\Internal\InternalItemResolver;
use Jrm\RequestBundle\DependencyInjection\Compiler\InternalValueResolverCompilerPass;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('jrm.request.internal_value_resolver.internal_collection_resolver', InternalCollectionResolver::class)
            ->args([
                '$internalRequestValuesCollector' => service('jrm.request.service.internal_request_values_collector'),
            ])
            ->tag(InternalValueResolverCompilerPass::INTERNAL_VALUE_RESOLVER_TAG)

        ->set('jrm.request.internal_value_resolver.internal_embeddable_request_resolver', InternalEmbeddableRequestResolver::class)
            ->args([
                '$internalRequestValuesCollector' => service('jrm.request.service.internal_request_values_collector'),
            ])
            ->tag(InternalValueResolverCompilerPass::INTERNAL_VALUE_RESOLVER_TAG)

        ->set('jrm.request.internal_value_resolver.internal_item_resolver', InternalItemResolver::class)
            ->tag(InternalValueResolverCompilerPass::INTERNAL_VALUE_RESOLVER_TAG);
};
