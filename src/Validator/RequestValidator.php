<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Validator;

use Jrm\RequestBundle\Exception\RequestValidationFailedException;
use Jrm\RequestBundle\Validator\Violation\Factory\ViolationFactory;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class RequestValidator
{
    public function __construct(
        private ValidatorInterface $validator,
        private ViolationFactory $violationFactory,
    ) {
    }

    public function validate(object $request): void
    {
        $violations = \array_map(
            [$this->violationFactory, 'createFromConstraintViolation'],
            iterator_to_array($this->validator->validate($request)),
        );

        if (count($violations) > 0) {
            throw new RequestValidationFailedException($violations);
        }
    }
}
