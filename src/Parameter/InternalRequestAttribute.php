<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Parameter;

use Jrm\RequestBundle\Model\Path;

interface InternalRequestAttribute extends RequestAttribute
{
    public function path(): Path;
}
