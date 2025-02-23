<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Validator\Violation\Factory;

use Jrm\RequestBundle\Validator\Violation\Violation;
use RuntimeException;
use Symfony\Component\Validator\ConstraintViolationInterface;

final class ViolationFactory
{
    public function createFromConstraintViolation(ConstraintViolationInterface $constraintViolation): Violation
    {
        $parameters = $this->normalizeParameterKeys($constraintViolation->getParameters());

        return new Violation(
            $constraintViolation->getMessage(),
            $parameters,
            $constraintViolation->getCode(),
            $constraintViolation->getPropertyPath(),
        );
    }

    /**
     * @param array<string, mixed> $parameters
     *
     * @return array<string, mixed>
     */
    private function normalizeParameterKeys(array $parameters): array
    {
        $normalizedParameters = [];

        foreach ($parameters as $key => $parameter) {
            if (preg_match('/{{ (?<name>\S+) }}/', $key, $matches) === 0) {
                throw new RuntimeException(sprintf('Invalid violation parameter name %s', $key));
            }

            $name = $matches['name'] ?? throw new RuntimeException(sprintf('Invalid violation parameter name %s', $key));
            $normalizedParameters[$name] = $parameter;
        }

        return $normalizedParameters;
    }
}
