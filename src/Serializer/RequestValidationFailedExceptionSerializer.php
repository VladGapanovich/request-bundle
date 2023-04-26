<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Serializer;

use Jrm\RequestBundle\Exception\RequestValidationFailedException;
use Jrm\RequestBundle\Validator\Violation\Violation;

final class RequestValidationFailedExceptionSerializer
{
    /**
     * @return array<string, mixed>
     */
    public function serialize(RequestValidationFailedException $exception): array
    {
        return [
            'message' => $exception->getMessage(),
            'errors' => array_map(
                [$this, 'serializeViolation'],
                $exception->violations(),
            ),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function serializeViolation(Violation $violation): array
    {
        $error = [
            'message' => $violation->message(),
            'parameters' => $violation->parameters(),
        ];

        if ($violation->code() !== null) {
            $error['code'] = $violation->code();
        }

        if ($violation->propertyPath() !== null) {
            $error['property_path'] = $violation->propertyPath();
        }

        return $error;
    }
}
