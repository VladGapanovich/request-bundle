<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Factory;

use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Contracts\Translation\TranslatorInterface;

final readonly class InvalidTypeConstraintViolationFactory
{
    public function __construct(
        private ?TranslatorInterface $translator = null,
    ) {
    }

    public function create(NotNormalizableValueException $exception): ConstraintViolation
    {
        $trans = $this->translator instanceof TranslatorInterface
            ? fn (string $id, array $parameters = [], ?string $domain = null, ?string $locale = null): string => $this->translator->trans($id, $parameters, $domain, $locale)
            : static fn ($m, $p) => strtr($m, $p);

        $parameters = ['{{ type }}' => implode('|', $exception->getExpectedTypes())];

        if ($exception->canUseMessageForUser()) {
            $parameters['hint'] = $exception->getMessage();
        }

        $template = 'This value should be of type {{ type }}.';
        $message = $trans($template, $parameters, 'validators');

        return new ConstraintViolation($message, $template, $parameters, null, $exception->getPath(), null);
    }
}
