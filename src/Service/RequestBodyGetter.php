<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Service;

use Jrm\RequestBundle\Validator\RequestFormatValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use WeakMap;

final class RequestBodyGetter
{
    /**
     * @var WeakMap<Request, array<array-key, mixed>>
     */
    private WeakMap $map;

    public function __construct(
        private RequestFormatValidator $requestFormatValidator,
        private DecoderInterface $decoder,
    ) {
        $this->map = new WeakMap();
    }

    /**
     * @return array<array-key, mixed>
     */
    public function get(Request $request): array
    {
        if ($this->map->offsetExists($request)) {
            return $this->map->offsetGet($request);
        }

        $this->requestFormatValidator->validate($request);

        $data = $request->request->all();

        if ($data !== []) {
            $this->map->offsetSet($request, $data);

            return $data;
        }

        $content = $request->getContent();

        if ($content === '') {
            $this->map->offsetSet($request, []);

            return [];
        }

        $format = $request->getContentTypeFormat();

        if ($format === 'form') {
            throw new BadRequestHttpException('Request body contains invalid "form" data.');
        }

        try {
            $data = $this->decoder->decode($content, $format);
        } catch (NotEncodableValueException $exception) {
            throw new BadRequestHttpException(sprintf('Request body contains invalid "%s" data.', $request->getContentTypeFormat()), $exception);
        }

        if (is_array($data)) {
            $this->map->offsetSet($request, $data);

            return $data;
        }

        throw new BadRequestHttpException(sprintf('Request body "%s" is invalid. Only array or object accepted', $content));
    }
}
