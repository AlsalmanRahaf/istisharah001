<?php

namespace App\Http\Resources;

use PHPUnit\Util\Exception;

abstract class MappingResource
{
    abstract public static function classesSupported() : array;

    public static function make($resourceClass, $resource){
        if(class_exists($resourceClass) && in_array($resourceClass, static::classesSupported()))
            $data = $resourceClass::make($resource);
        else
            throw new Exception("The Class of Resource Is no Exists");
        return $data;
    }

    public static function collection($resourceClass, $resources){
        if(class_exists($resourceClass) && in_array($resourceClass, static::classesSupported()))
            $data = $resourceClass::collection($resources);
        else
            throw new Exception("The Class of Resource Is no Exists");
        return $data;
    }
}
