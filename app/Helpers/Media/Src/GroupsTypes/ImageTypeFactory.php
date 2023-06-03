<?php

namespace App\Helpers\Media\Src\GroupsTypes;

class ImageTypeFactory
{
    public static function createImageGroup($type) : ImageType{
        try {
            switch ($type){
                case "single":
                    $group = new SingleImage();
                    break;
                case "multi":
                    $group = new MultiImage();
                    break;
                default:
                    throw new \Exception("The Group Types of Media is not exists");
                break;
            }
            return $group;
        }catch (\Exception $e){
            die($e->getMessage());
        }

    }

}
