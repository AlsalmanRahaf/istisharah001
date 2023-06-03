<?php


namespace App\Helpers\ApiResponse\Json\Senders;


class SendError extends Sender
{

    public function __construct() {
        $this->status = false;
        $this->statusNumber = 'S500';
        $this->code = 500;
    }

}
