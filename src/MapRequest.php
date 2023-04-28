<?php

declare(strict_types=1);

namespace Jrm\RequestBundle;

use Attribute;
use Symfony\Component\Validator\Constraints\GroupSequence;

#[Attribute(Attribute::TARGET_PARAMETER)]
final class MapRequest
{
    /**
     * @param string[]|string|null $acceptFormat
     * @param array<array-key, mixed> $serializationContext
     * @param string|GroupSequence|string[]|null $validationGroups
     */
    public function __construct(
        public array|string|null $acceptFormat = null,
        public array $serializationContext = [],
        public string|GroupSequence|array|null $validationGroups = null,
    ) {
    }
}
