<?php

namespace App\Helpers\Media\Src\GroupsTypes;

class SingleImage extends ImageType
{
    protected function numberOfType() : int
    {
        return 1;
    }
}
