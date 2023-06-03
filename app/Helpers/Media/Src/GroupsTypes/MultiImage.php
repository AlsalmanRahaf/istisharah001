<?php

namespace App\Helpers\Media\Src\GroupsTypes;

class MultiImage extends ImageType
{
    protected function numberOfType() : int
    {
        return 2;
    }
}
