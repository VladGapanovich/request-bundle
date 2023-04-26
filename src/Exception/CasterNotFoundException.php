<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Exception;

use RuntimeException;

final class CasterNotFoundException extends RuntimeException implements RequestBundleException
{
    public function __construct(string $type)
    {
        parent::__construct(sprintf('Caster for type "%s" not found.', $type));
    }
}
