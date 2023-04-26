<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Registry;

use Jrm\RequestBundle\Caster\Caster;
use Jrm\RequestBundle\Exception\CasterNotFoundException;

final class CasterRegistry
{
    /**
     * @param array<string, Caster> $casters
     */
    public function __construct(
        private array $casters = [],
    ) {
    }

    public function register(Caster $caster): void
    {
        $this->casters[$caster::getType()] = $caster;
    }

    public function getCaster(string $type): Caster
    {
        if (array_key_exists($type, $this->casters)) {
            return $this->casters[$type];
        }

        if (class_exists($type)) {
            return $this->tryToResolveCasterByClassName($type);
        }

        throw new CasterNotFoundException($type);
    }

    /**
     * @param class-string $className
     */
    private function tryToResolveCasterByClassName(string $className): Caster
    {
        $types = array_merge(
            class_implements($className) ?: [],
            class_parents($className) ?: [],
        );

        foreach ($types as $type) {
            if (array_key_exists($type, $this->casters)) {
                return $this->casters[$type];
            }
        }

        throw new CasterNotFoundException($className);
    }
}
