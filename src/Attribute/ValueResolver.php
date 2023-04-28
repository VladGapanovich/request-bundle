<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Attribute;

use Jrm\RequestBundle\Model\Metadata;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

interface ValueResolver
{
    /**
     * @throws Throwable
     */
    public function resolve(Request $request, Metadata $metadata, RequestAttribute $attribute): mixed;
}
