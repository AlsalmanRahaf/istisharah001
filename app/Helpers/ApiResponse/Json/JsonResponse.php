<?php


namespace App\Helpers\ApiResponse\Json;

use App\Helpers\ApiResponse\Json\Senders\SendData;
use App\Helpers\ApiResponse\Json\Senders\Sender;
use App\Helpers\ApiResponse\Json\Senders\SendError;
use App\Helpers\ApiResponse\Json\Senders\SendSuccess;
use App\Helpers\ApiResponse\Json\Senders\SendValidationError;


abstract class JsonResponse{

    protected  static $sender;

    public static function success() : SendSuccess{
       return static::$sender = new SendSuccess();
    }

    public static function error() : SendError{
        return static::$sender = new SendError();
    }

    public static function validationErrors($errors) : SendValidationError{
        return static::$sender = new SendValidationError($errors);
    }

    public static function data($data) : SendData{
        return static::$sender = new SendData($data);
    }


}
