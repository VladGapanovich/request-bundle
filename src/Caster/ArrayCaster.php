<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Caster;

use Jrm\RequestBundle\Exception\InvalidTypeException;
use Jrm\RequestBundle\Exception\UnexpectedAttributeException;
use Jrm\RequestBundle\Exception\UnexpectedCollectionTypeException;
use Jrm\RequestBundle\Factory\ItemFactory;
use Jrm\RequestBundle\Model\BaseType;
use Jrm\RequestBundle\Parameter\Collection as CollectionParameter;
use Jrm\RequestBundle\Parameter\RequestAttribute;
use Jrm\RequestBundle\Registry\CasterRegistry;

final class ArrayCaster implements Caster
{
    /**
     * @param string[] $availableTypes
     */
    public function __construct(
        private CasterRegistry $casterRegistry,
        private ItemFactory $itemFactory,
        private array $availableTypes,
    ) {
    }

    public static function getType(): string
    {
        return BaseType::ARRAY;
    }

    /**
     * @return array<int, mixed>|null
     */
    public function cast(mixed $value, RequestAttribute $attribute, string $type, bool $allowsNull): ?array
    {
        if (!$attribute instanceof CollectionParameter) {
            throw new UnexpectedAttributeException(CollectionParameter::class, $attribute::class);
        }

        if ($value === null && $allowsNull) {
            return null;
        }

        if (!is_array($value)) {
            throw new InvalidTypeException(BaseType::ARRAY, get_debug_type($value));
        }

        if (in_array($attribute->type(), $this->availableTypes, true)) {
            return $this->castToCollectionOfAvailableTypes($value, $attribute);
        }

        if (class_exists($attribute->type())) {
            return $this->castToCollectionOfItems($value, $attribute->type());
        }

        throw new UnexpectedCollectionTypeException($attribute->type(), $this->availableTypes);
    }

    /**
     * @param mixed[] $value
     *
     * @return mixed[]
     */
    private function castToCollectionOfAvailableTypes(array $value, CollectionParameter $attribute): array
    {
        $caster = $this->casterRegistry->getCaster($attribute->type());

        return array_map(
            fn (mixed $item): mixed => $caster->cast($item, $attribute, $attribute->type(), false),
            $value,
        );
    }

    /**
     * @template T
     *
     * @param mixed[] $value
     * @param class-string<T> $type
     *
     * @return T[]
     */
    private function castToCollectionOfItems(array $value, string $type): array
    {
        return array_map(
            function (mixed $item) use ($type): object {
                if (!is_array($item)) {
                    throw new InvalidTypeException(BaseType::ARRAY, get_debug_type($item));
                }

                return $this->itemFactory->create($type, $item);
            },
            $value,
        );
    }
}
