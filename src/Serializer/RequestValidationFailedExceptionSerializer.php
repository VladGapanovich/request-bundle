<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Serializer;

use Jrm\RequestBundle\Exception\RequestValidationFailedException;
use Symfony\Component\Validator\ConstraintViolation;

final class RequestValidationFailedExceptionSerializer
{
    /**
     * @return array<string, mixed>
     */
    public function serialize(RequestValidationFailedException $exception): array
    {
        $response = ['message' => $exception->getMessage()];

        if ($exception->violations() !== null) {
            $response['errors'] = array_map(
                [$this, 'serializeViolation'],
                iterator_to_array($exception->violations()),
            );
        }

        return $response;
    }

    /**
     * @return array<string, mixed>
     */
    private function serializeViolation(ConstraintViolation $violation): array
    {
        $error = [
            'message' => (string) $violation->getMessage(),
            'parameters' => $violation->getParameters(),
        ];

        if ($violation->getCode() !== null) {
            $error['code'] = $violation->getCode();
        }

        if ($violation->getPropertyPath() !== '') {
            $error['property_path'] = $violation->getPropertyPath();
        }

        return $error;
    }
}
