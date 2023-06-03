<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

function gateCRUDPermissions($mainName){
    return Gate::check("view-" . $mainName) || Gate::check("create-" . $mainName)
        || Gate::check("update-" . $mainName) || Gate::check("delete-" . $mainName);
}

function isJson($string){
    $data = json_decode($string, true);
    if (is_null($data) || !is_array($data))
       return false;
    return true;
}
function isArabic($string){
    if(mb_detect_encoding($string[0]) == "UTF-8")
        return true;
    return false;

}

function hasPermissions($permissions){
    $user = Auth::user();
//    return $user->role->permissions;



    if($permissions == "admin-control"){
        if($user->is_super_admin == 1)
            return true;
        return false;
    }

    if($user->is_super_admin == 1)
        return true;

    if(is_array($permissions)){
        foreach ($permissions as $permission){
            if(Gate::allows($permission)){
                return true;
            }
        }
    }else{
        if(Gate::allows($permissions)){
            return true;
        }
    }
    return false;
}


function getSameWithNewLanguage($lang){
    $path = request()->path();
    for ($char = 0; strlen($path); $char++){
        if($path[$char] !== "/")
            $path[$char] = ' ';
        else
            break;
    }
    $path = trim($path, " /");
    return "/" . $lang . "/" . $path;
}


function generateRandomStringAndNumber($length = 16){
    $chars = ['a', 'b', 'c', 'd', 'e', 'f', 'r', 't', 'z', 'm', 'q', 'w', 'n', 'b',
        'A', 'B', 'C', 'D', 'E', 'F', 'R', 'T', 'Z', 'M', 'Q', 'S', 'X', 'V', 1, 2, 3, 4, 5, 6, 7, 8, 9, 0];
    $string = '';
    for ($i=0; $i < $length; $i++){
        $string .= $chars[rand(0, 37)];
    }
    return $string;
}


