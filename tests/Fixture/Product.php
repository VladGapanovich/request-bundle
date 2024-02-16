<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Tests\Fixture;

use Jrm\RequestBundle\Attribute\Collection;
use Jrm\RequestBundle\Attribute\EmbeddableRequest;
use Jrm\RequestBundle\Attribute\Internal\Item;
use Symfony\Component\Validator\Constraints as Assert;

final class Product
{
    public function __construct(
        #[Assert\Uuid]
        #[Item]
        public string $id,
        /**
         * @var string[]
         */
        #[Assert\Count(min: 2)]
        #[Item]
        public array $tags,
        #[Assert\Valid]
        #[EmbeddableRequest]
        public Image $image,
        /**
         * @var Ingredient[]
         */
        #[Assert\Valid]
        #[Collection(type: Ingredient::class)]
        public array $ingredients,
    ) {
    }
}
