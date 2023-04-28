<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Tests\Fixture;

use Jrm\RequestBundle\Attribute\Body;
use Jrm\RequestBundle\Attribute\Cookie;
use Jrm\RequestBundle\Attribute\EmbeddableRequest;
use Jrm\RequestBundle\Attribute\Header;
use Jrm\RequestBundle\Attribute\PathAttribute;
use Symfony\Component\Validator\Constraints as Assert;

final class ComplicatedRequestStub
{
    public function __construct(
        #[Assert\Uuid]
        #[PathAttribute]
        public string $id,
        #[EmbeddableRequest]
        public Product $product,
        #[Assert\Length(min: 1)]
        #[Header('Content-Type')]
        public string $contentType,
        #[Assert\Length(min: 1)]
        #[Body]
        public string $name,
        #[Assert\Length(min: 1)]
        #[Cookie]
        public string $authToken,
    ) {
    }
}
