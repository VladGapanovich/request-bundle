<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Attribute;

use Attribute;
use Jrm\RequestBundle\Model\Name;

#[Attribute(Attribute::TARGET_PARAMETER | Attribute::TARGET_PROPERTY)]
final class File implements RequestAttribute
{
    private ?Name $name;

    public function __construct(?string $name = null)
    {
        $this->name = $name !== null ? new Name($name) : null;
    }

    /**
     * @return class-string<FileResolver>
     */
    public function resolvedBy(): string
    {
        return FileResolver::class;
    }

    public function name(): ?Name
    {
        return $this->name;
    }
}
