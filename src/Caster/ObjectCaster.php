<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Caster;

use Jrm\RequestBundle\Exception\InvalidTypeException;
use Jrm\RequestBundle\Exception\UnexpectedAttributeException;
use Jrm\RequestBundle\Factory\ItemFactory;
use Jrm\RequestBundle\Model\BaseType;
use Jrm\RequestBundle\Parameter\EmbeddableRequest;
use Jrm\RequestBundle\Parameter\RequestAttribute;
use stdClass;

final class ObjectCaster implements Caster
{
    public function __construct(
        private ItemFactory $itemFactory,
    ) {
    }

    public static function getType(): string
    {
        return BaseType::OBJECT;
    }

    /**
     * @template T of stdClass
     *
     * @param class-string<T> $type
     *
     * @return ?T
     */
    public function cast(mixed $value, RequestAttribute $attribute, string $type, bool $allowsNull): ?object
    {
        if (!$attribute instanceof EmbeddableRequest) {
            throw new UnexpectedAttributeException(EmbeddableRequest::class, $attribute::class);
        }

        if ($value === null && $allowsNull) {
            return null;
        }

        if (!is_array($value)) {
            throw new InvalidTypeException(BaseType::ARRAY, get_debug_type($value));
        }

        if (class_exists($type)) {
            return $this->itemFactory->create($type, $value);
        }

        return (object) $value;
    }
}
