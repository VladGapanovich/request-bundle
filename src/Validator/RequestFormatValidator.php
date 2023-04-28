<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Validator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;

final class RequestFormatValidator
{
    /**
     * @param string[] $acceptedFormats
     */
    public function validate(Request $request, array|string|null $acceptedFormat = null): void
    {
        $format = $request->getContentTypeFormat();

        if ($format === null) {
            throw new UnsupportedMediaTypeHttpException('Unsupported format.');
        }

        if ($acceptedFormat && !\in_array($format, (array) $acceptedFormat, true)) {
            throw new UnsupportedMediaTypeHttpException(sprintf('Unsupported format, expects "%s", but "%s" given.', implode('", "', (array) $acceptedFormat), $format));
        }
    }
}
