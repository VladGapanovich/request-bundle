<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Extractor;

use Jrm\RequestBundle\Attribute\Collection;
use ReflectionProperty;
use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use Symfony\Component\PropertyInfo\Type;

final class CollectionReflectionExtractor implements PropertyTypeExtractorInterface
{
    public function __construct(
        private PropertyTypeExtractorInterface $propertyTypeExtractor,
    ) {
    }

    /**
     * @param array<array-key, mixed> $context
     *
     * @return Type[]|null
     */
    public function getTypes(string $class, string $property, array $context = []): ?array
    {
        $types = $this->propertyTypeExtractor->getTypes($class, $property, $context);

        if ($types === null || count($types) !== 1 || $types[0]->getBuiltinType() !== Type::BUILTIN_TYPE_ARRAY) {
            return $types;
        }

        $reflectionProperty = new ReflectionProperty($class, $property);
        $collectionAttributes = $reflectionProperty->getAttributes(Collection::class);

        return [new Type(
            Type::BUILTIN_TYPE_ARRAY,
            false,
            null,
            true,
            new Type(Type::BUILTIN_TYPE_INT),
            new Type(Type::BUILTIN_TYPE_OBJECT, false, $collectionAttributes[0]->newInstance()->type()),
        )];
    }
}
