<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Tests\Fixture;

use Jrm\RequestBundle\Attribute\EmbeddableRequest;
use Jrm\RequestBundle\Attribute\Internal\Item;
use Symfony\Component\Validator\Constraints as Assert;

final class Ingredient
{
    public function __construct(
        #[Assert\Uuid]
        #[Item]
        public string $id,
        #[Assert\Valid]
        #[EmbeddableRequest]
        public Image $image,
    ) {
    }
}
