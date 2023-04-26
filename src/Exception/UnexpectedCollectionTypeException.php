<?php

declare(strict_types=1);

namespace Jrm\RequestBundle\Exception;

use LogicException;

final class UnexpectedCollectionTypeException extends LogicException implements RequestBundleException
{
    /**
     * @param string[] $availableTypes
     */
    public function __construct(string $type, array $availableTypes)
    {
        parent::__construct(sprintf(
            'Unexpected type. Expected Available type or any class, got %s. Available types: %s',
            $type,
            implode(', ', $availableTypes),
        ));
    }
}
