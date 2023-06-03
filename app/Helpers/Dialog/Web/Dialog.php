<?php

namespace App\Helpers\Dialog\Web;


class Dialog
{

    public static function flashing(Message $message)
    {
        $data["title"] = $message->getTitle();
        $data["body"] = $message->getBody();
        $data["type"] = $message->getType();
        \session()->flash($message->getAccessKey(), $data);
    }
}
