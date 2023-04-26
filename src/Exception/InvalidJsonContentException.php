<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Exception;

use Jrm\RequestBundle\Parameter\Body;
use RuntimeException;

final class InvalidJsonContentException extends RuntimeException implements RequestBundleException
{
    public function __construct(Body $parameter)
    {
        parent::__construct(sprintf('Cannot resolve body parameter with path: %s. Json content is invalid', $parameter->path()->value()));
    }
}
