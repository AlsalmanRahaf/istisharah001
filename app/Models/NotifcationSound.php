<?php

namespace App\Models;

use App\Helpers\Media\Src\MediaGroups;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotifcationSound extends Model
{
    use HasFactory;

    const IMAGE_PATH = "SocialMedia";
    public function setMainDirectoryPath(): string
    {
        return self::IMAGE_PATH;
    }


    public function setGroups(): MediaGroups
    {
        return (new MediaGroups())->setGroup("single", "SocialMedia", DS);
    }
}
