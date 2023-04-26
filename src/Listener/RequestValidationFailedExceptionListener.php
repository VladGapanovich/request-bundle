<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Listener;

use Jrm\RequestBundle\Exception\RequestValidationFailedException;
use Jrm\RequestBundle\Serializer\RequestValidationFailedExceptionSerializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

final class RequestValidationFailedExceptionListener
{
    public function __construct(
        private RequestValidationFailedExceptionSerializer $serializer,
    ) {
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof RequestValidationFailedException) {
            return;
        }

        $event->setResponse(new JsonResponse($this->serializer->serialize($exception), Response::HTTP_BAD_REQUEST));
    }
}
