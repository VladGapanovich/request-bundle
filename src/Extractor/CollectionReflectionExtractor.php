<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Extractor;

use Jrm\RequestBundle\Attribute\Collection;
use ReflectionProperty;
use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use Symfony\Component\PropertyInfo\Type as LegacyType;
use Symfony\Component\TypeInfo\Type;
use Symfony\Component\TypeInfo\Type\ObjectType;
use Symfony\Component\TypeInfo\TypeIdentifier;

final readonly class CollectionReflectionExtractor implements PropertyTypeExtractorInterface
{
    public function __construct(
        private PropertyTypeExtractorInterface $propertyTypeExtractor,
    ) {
    }

    /**
     * @param array<array-key, mixed> $context
     *
     * @return LegacyType[]|null
     */
    public function getTypes(string $class, string $property, array $context = []): ?array
    {
        $types = $this->propertyTypeExtractor->getTypes($class, $property, $context);

        if ($types === null || count($types) !== 1 || $types[0]->getBuiltinType() !== LegacyType::BUILTIN_TYPE_ARRAY) {
            return $types;
        }

        $reflectionProperty = new ReflectionProperty($class, $property);
        $collectionAttributes = $reflectionProperty->getAttributes(Collection::class);

        if ($collectionAttributes === []) {
            return $types;
        }

        return [new LegacyType(
            LegacyType::BUILTIN_TYPE_ARRAY,
            false,
            null,
            true,
            new LegacyType(LegacyType::BUILTIN_TYPE_INT),
            new LegacyType(LegacyType::BUILTIN_TYPE_OBJECT, false, $collectionAttributes[0]->newInstance()->type()),
        )];
    }

    /**
     * @param array<array-key, mixed> $context
     */
    public function getType(string $class, string $property, array $context = []): ?Type
    {
        $type = $this->propertyTypeExtractor->getType($class, $property, $context);
        $isArrayType = false;

        if ($type instanceof Type) {
            if (method_exists($type, 'isIdentifiedBy')) {
                $isArrayType = $type->isIdentifiedBy(TypeIdentifier::ARRAY);
            } elseif (method_exists($type, 'isA')) {
                $isArrayType = $type->isA(TypeIdentifier::ARRAY);
            }
        }

        if (!$isArrayType) {
            return $type;
        }

        $reflectionProperty = new ReflectionProperty($class, $property);
        $collectionAttributes = $reflectionProperty->getAttributes(Collection::class);

        if ($collectionAttributes === []) {
            return $type;
        }

        return Type::array(
            new ObjectType($collectionAttributes[0]->newInstance()->type()),
            Type::union(Type::int(), Type::string()),
        );
    }
}
