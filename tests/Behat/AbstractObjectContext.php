<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use Symfony\Component\PropertyAccess\PropertyAccess;

abstract class AbstractObjectContext
{
    protected function fillObject($object, array $data): void
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        foreach ($data as $column => $value) {
            $propertyAccessor->setValue($object, $column, $value);
        }
    }
}
