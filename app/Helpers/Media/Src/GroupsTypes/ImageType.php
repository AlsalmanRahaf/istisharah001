<?php

namespace App\Helpers\Media\Src\GroupsTypes;

abstract class ImageType
{
    protected $name;
    protected $savingPath;
    protected $type;


    public function __construct()
    {
        $this->type = $this->numberOfType();
    }

    protected abstract function numberOfType() : int;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getSavingPath()
    {
        return $this->savingPath;
    }

    /**
     * @param mixed $savingPath
     */
    public function setSavingPath($savingPath): void
    {
        $this->savingPath = $savingPath;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }


}
