<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Parameter;

use Jrm\RequestBundle\Model\Path;
use Jrm\RequestBundle\Service\PropertyAccessor;
use ReflectionParameter;
use Throwable;

final class ItemResolver
{
    /**
     * @param array<string, mixed> $data
     */
    public function resolve(
        array $data,
        ReflectionParameter $parameter,
        Path $path,
    ): mixed {
        try {
            return PropertyAccessor::get($data, $path->valueAsArrayKeyPath());
        } catch (Throwable $throwable) {
            if ($parameter->isOptional()) {
                return $parameter->getDefaultValue();
            }

            throw $throwable;
        }
    }
}
