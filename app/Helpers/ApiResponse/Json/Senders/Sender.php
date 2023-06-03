<?php


namespace App\Helpers\ApiResponse\Json\Senders;


abstract class Sender
{
    protected $response = [];
    protected $status = true;
    protected $statusNumber = 'S200';
    protected $code = 200;
    protected $headers = [];



    public function send(){
        $response["status"] = $this->status;
        $response["status_number"] = $this->statusNumber;
        if(!empty($this->response))
            $response = array_merge($response, $this->response);
        return response()->json($response, $this->code, $this->headers);
    }

    public function changeCode($code){
         $this->code = $code;
         $this->changeStatusNumber("S" . $code);
         return $this;
    }

    public function message($message){
        $this->response["message"] = $message;
        return $this;
    }

    public function changeStatusNumber(String $number){
        $this->statusNumber = $number;
        return $this;
    }

    public function headers(Array $headers){
        $this->headers = $headers;
        return $this;
    }


}
