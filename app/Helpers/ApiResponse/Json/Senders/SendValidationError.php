<?php


namespace App\Helpers\ApiResponse\Json\Senders;


class SendValidationError extends Sender
{

    public function __construct($errors) {
        $this->response["errors"] = $errors;
        $this->statusNumber = "S400";
        $this->code = 400;
        $this->status = false;
    }

    public function initAjaxRequest(){
        $messages = [];
        foreach ($this->response["errors"] as $fieldname => $message){
            if(is_numeric($fieldname[-1])){
                $field = explode(".",$fieldname, 2);
                if(count($field) > 1)
                    $messages[$field[0]][$fieldname[-1]] = $message[0];
                else
                    $messages[$fieldname] = $message[0];
            }else{
                $messages[$fieldname] = $message[0];
            }
        }
        $this->response["errors"] = $messages;
        return $this;
    }

}
