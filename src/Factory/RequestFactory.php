<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Factory;

use Jrm\RequestBundle\Exception\RequestValidationFailedException;
use Jrm\RequestBundle\MapRequest;
use Symfony\Component\Serializer\Exception\PartialDenormalizationException;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class RequestFactory
{
    private const DEFAULT_CONTEXT = [
        AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => false,
        DenormalizerInterface::COLLECT_DENORMALIZATION_ERRORS => true,
    ];

    public function __construct(
        private DenormalizerInterface $serializer,
        private InvalidTypeConstraintViolationFactory $invalidTypeConstraintViolationFactory,
        private ?ValidatorInterface $validator = null,
    ) {
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $className
     * @param array<array-key, mixed> $requestData
     *
     * @return T
     */
    public function create(MapRequest $attribute, string $className, array $requestData): object
    {
        if ($this->validator instanceof ValidatorInterface) {
            $violations = new ConstraintViolationList();

            try {
                $payload = $this->serializer->denormalize(
                    $requestData,
                    $className,
                    null,
                    self::DEFAULT_CONTEXT + $attribute->serializationContext,
                );
            } catch (PartialDenormalizationException $exception) {
                foreach ($exception->getErrors() as $error) {
                    $violations->add($this->invalidTypeConstraintViolationFactory->create($error));
                }

                $payload = $exception->getData();
            }

            if ($payload instanceof $className) {
                $violations->addAll($this->validator->validate($payload, null, $attribute->validationGroups ?? null));
            }

            if (\count($violations) > 0) {
                throw new RequestValidationFailedException(422, 'Request validation failed.', $violations);
            }
        } else {
            try {
                $payload = $this->serializer->denormalize(
                    $requestData,
                    $className,
                    null,
                    self::DEFAULT_CONTEXT + $attribute->serializationContext,
                );
            } catch (PartialDenormalizationException $exception) {
                throw new RequestValidationFailedException(422, implode(PHP_EOL, array_map(static fn ($exception) => $exception->getMessage(), $exception->getErrors())), null, $exception);
            }
        }

        if (!$payload instanceof $className) {
            throw new RequestValidationFailedException(415, 'Request payload contains invalid data.');
        }

        return $payload;
    }
}
