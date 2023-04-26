<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Parameter;

use ReflectionParameter;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

interface ParameterResolver
{
    /**
     * @throws Throwable
     */
    public function resolve(Request $request, ReflectionParameter $parameter, RequestAttribute $attribute): mixed;
}
