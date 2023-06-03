<?php
namespace App\Traits;

trait ApiResponse{

    public function sendError($msg = "something wrong", $statusNumber = "404", $code = 404){
        return response()->json([
            "status"        => false,
            "status_number" => $statusNumber,
            "message"       => $msg
        ], $code);
    }

    public function sendSuccess($msg = "success", $statusNumber = "200", $code = 200){
        return response()->json([
            "status"        => true,
            "status_number" => $statusNumber,
            "message"       => $msg
        ], $code);
    }

    public function sendData($data, $msg = "success",$statusNumber = "200", $code = 200){
        return response()->json([
            "status"        => true,
            "status_number" => $statusNumber,
            "message"       => $msg,
            "data"          => $data
        ], $code);
    }

    // public function returnValidationErrors($validation_messages){
    //     $messages = [];
    //     foreach ($validation_messages as $fieldname => $message){
    //         $messages[$fieldname] = $message[0];
    //     }
    //     return $this->returnData("validation_messages", $messages, "validation error", 406);
    // }



}
