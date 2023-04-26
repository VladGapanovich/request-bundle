<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\DependencyInjection;

use Jrm\RequestBundle\Caster\Caster;
use Jrm\RequestBundle\DependencyInjection\Compiler\CasterCompilerPass;
use Jrm\RequestBundle\DependencyInjection\Compiler\ParameterResolverCompilerPass;
use Jrm\RequestBundle\Parameter\ParameterResolver;
use LogicException;
use ReflectionClass;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class JrmRequestExtension extends Extension
{
    /**
     * @param array<string, mixed> $configs
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../../config'),
        );
        $loader->load('services.php');

        $container->registerForAutoconfiguration(Caster::class)
            ->addTag(CasterCompilerPass::CASTER_TAG);
        $container->registerForAutoconfiguration(ParameterResolver::class)
            ->addTag(ParameterResolverCompilerPass::PARAMETER_RESOLVER_TAG);

        $this->setAvailableCasterTypes($container);
    }

    private function setAvailableCasterTypes(ContainerBuilder $container): void
    {
        $serviceIds = array_keys($container->findTaggedServiceIds(CasterCompilerPass::CASTER_TAG));
        $types = array_map(
            static function (string $serviceId) use ($container): string {
                $class = $container->getDefinition($serviceId)->getClass();

                if (
                    $class !== null
                    && class_exists($class)
                    && (new ReflectionClass($class))->isSubclassOf(Caster::class)
                ) {
                    return $class::getType();
                }

                throw new LogicException(sprintf('The tag "%s" should to have only classes, that implement %s interface', CasterCompilerPass::CASTER_TAG, Caster::class));
            },
            $serviceIds,
        );

        $container->setParameter('jrm.request.caster.available_types', $types);
    }
}
