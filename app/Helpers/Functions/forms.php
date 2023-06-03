<?php

use Illuminate\Support\Facades\App;

function inputValue($key, $object = null, $attribute = null, $attribute_key = null){

    return request()->old($key) !== null ? request()->old($key) :
        ( is_object($object) ? ( $object->$key ? $object->$key :
            ( $attribute !== null && $attribute_key == null ? ( $object->$attribute ? $object->$attribute : null)
                : (isset($object->$attribute[$attribute_key]) ? $object->$attribute[$attribute_key] : null) )) : null);
}


function selected($key, $match, $object = null, $attribute = null){
    $result = '';
    if(request()->old($key) !== null && request()->old($key) == $match){
        $result = "selected";
    }else if(request()->old($key) === null){
        if($attribute === null && is_object($object) && $object->$key !== null && $object->$key == $match){
            $result = "selected";
        }else if($attribute !== null){
            if(is_object($object) && $object->$attribute !== null && $object->$attribute == $match){
                $result = "selected";
            }
        }
    }
    return $result;
}


function checked($key,  $match, $object = null, $attribute = null){  //V.2
    $result = '';
    $value = '';

    if(request()->old($key) !== null){
        $value = request()->old($key);
    }else if(is_object($object) && $object->$key !== null && $attribute === null){
        $value = $object->$key;
    }else if(is_object($object) && $object->$attribute !== null){
        $value = $object->$attribute;
    }else{
        return null;
    }

    if(!is_array($match)){
        if($value == $match){
            $result = "checked";
        }
    }else{
        if(in_array($value, $match))
            $result = "checked";
    }
    return $result;
}


function customChecked($value,  $match){  //V.2
    $result = '';


    if(!is_array($match)){
        if($value == $match){
            $result = "checked";
        }
    }else{
        if(isset($match[$value]))
            $result = "checked";
    }
    return $result;
}

function checkedFromGroup($groupName, $key, $match, $object = null, $attribute = null, $attributeMatch = null){  //V.2
    $result = '';
    $value = '';
    $group = request()->old($groupName);
    if(isset($group[$key])){
        $value = $group[$key];
    }else if(is_object($object) && $object->$key !== null && $attribute === null){
        $value = $object->$key;
    }else if(is_object($object) && $object->$attribute !== null){
        $value = $object->$attribute;
        if($attributeMatch !== null)
            $match = $attributeMatch;
    }else{
        return null;
    }

    if(!is_array($match)){
        if($value == $match){
            $result = "checked";
        }
    }else{
        if(isset($match[$value]))
            $result = "checked";
    }
    return $result;
}


function routex($route, $params = [])
{
    if (!is_array($params)) {
        $params = [$params];
    }

    // Set the first parameter to App::getLocale()
    array_unshift($params, App::getLocale());
    return route($route, $params);
}



