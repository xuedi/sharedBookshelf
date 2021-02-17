<?php declare(strict_types=1);

namespace SharedBookshelf\Entities;

use ReflectionClass;

trait ReflectiveSetterForId
{
    public function setDoctrineId($entity, $value, $propertyName = 'id')
    {
        $class = new ReflectionClass($entity);
        $property = $class->getProperty($propertyName);
        $property->setAccessible(true);

        $property->setValue($entity, $value);
    }
}
