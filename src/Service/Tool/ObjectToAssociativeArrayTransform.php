<?php

declare(strict_types=1);

namespace App\Service\Tool;

use ReflectionClass;

class ObjectToAssociativeArrayTransform
{
    /**
     * @param object $objectToTransform
     * @return array
     * @throws \ReflectionException
     */
    function transform(object $objectToTransform): array
    {
        $reflectionClass = new ReflectionClass(get_class($objectToTransform));
        $transformedArray = array();

        foreach ($reflectionClass->getProperties() as $property) {
            $property->setAccessible(true);
            $transformedArray[$property->getName()] = $property->getValue($objectToTransform);
            $property->setAccessible(false);
        }

        return $transformedArray;
    }
}