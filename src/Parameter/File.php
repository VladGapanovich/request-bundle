<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Parameter;

use Attribute;
use Jrm\RequestBundle\Model\Name;

#[Attribute(Attribute::TARGET_PARAMETER | Attribute::TARGET_PROPERTY)]
final class File implements RequestAttribute
{
    private Name $name;

    public function __construct(string $name)
    {
        $this->name = new Name($name);
    }

    public function name(): Name
    {
        return $this->name;
    }

    /**
     * @return class-string<FileResolver>
     */
    public function resolvedBy(): string
    {
        return FileResolver::class;
    }
}
