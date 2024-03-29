<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Attribute;

use Attribute;
use Jrm\RequestBundle\Model\Name;

#[Attribute(Attribute::TARGET_PARAMETER | Attribute::TARGET_PROPERTY)]
final class Header implements RequestAttribute
{
    private readonly ?Name $name;

    public function __construct(?string $name = null)
    {
        $this->name = $name !== null ? new Name($name) : null;
    }

    /**
     * @return class-string<HeaderResolver>
     */
    public function resolvedBy(): string
    {
        return HeaderResolver::class;
    }

    public function name(): ?Name
    {
        return $this->name;
    }
}
