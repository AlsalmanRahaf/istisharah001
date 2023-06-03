<?php

namespace App\Helpers\Dialog\Web;


abstract class Message
{
    protected $title;
    protected $body;
    protected $type;
    protected $accessKey;

    public function __construct($type){
        $this->title = __("default title");
        $this->body = __("default body");
        $this->accessKey = "page-message";
        $this->type = $type;
    }


    public function title($title): Message
    {
        $this->title = __($title);
        return $this;
    }

    public function body($body): Message
    {
        $this->body = __($body);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getAccessKey(): string
    {
        return $this->accessKey;
    }




}
