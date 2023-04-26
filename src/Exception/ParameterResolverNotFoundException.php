<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Exception;

use LogicException;

final class ParameterResolverNotFoundException extends LogicException implements RequestBundleException
{
    public function __construct(string $id)
    {
        parent::__construct(sprintf('Parameter resolver "%s" not found.', $id));
    }
}
