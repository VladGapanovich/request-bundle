<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Exception;

use LogicException;

final class InternalValueResolverNotFoundException extends LogicException implements RequestBundleException
{
    public function __construct(string $id)
    {
        parent::__construct(sprintf('Internal value resolver "%s" not found.', $id));
    }
}
