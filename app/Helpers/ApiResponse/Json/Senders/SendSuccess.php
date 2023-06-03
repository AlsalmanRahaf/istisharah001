<?php


namespace App\Helpers\ApiResponse\Json\Senders;


class SendSuccess extends Sender
{
    protected $flash;
    public function __construct() {
        $this->statusNumber = 'S201';
        $this->code = 201;
    }

}
