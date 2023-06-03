<?php

namespace App\Helpers\Media\Src;

use App\Helpers\Media\Src\GroupsTypes\ImageType;
use App\Helpers\Media\Src\GroupsTypes\ImageTypeFactory;

class MediaGroups
{
    /**
     * @param ImageType[] $groups
     */
    protected array $groups = [];

    public  function setGroup($type, $name, $path){
        $group = ImageTypeFactory::createImageGroup($type);
        $group->setName($name);
        $group->setSavingPath($path);
        array_push($this->groups, $group);
        return $this;
    }

    public  function getAllGroups(): array
    {
        return $this->groups;
    }
}
