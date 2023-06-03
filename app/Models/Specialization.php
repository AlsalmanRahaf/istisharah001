<?php

namespace App\Models;

use App\Helpers\Media\Src\IMedia;
use App\Helpers\Media\Src\MediaGroups;
use App\Helpers\Media\Src\MediaInitialization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialization extends Model implements IMedia
{
    use HasFactory,MediaInitialization;

    protected $fillable = ["name_en", "name_ar", "description"];

    const IMAGE_PATH = "Specializations";
    public function setMainDirectoryPath(): string
    {
        return self::IMAGE_PATH;
    }


    public function setGroups(): MediaGroups
    {
        return (new MediaGroups())->setGroup("single", "Specializations", DS);
    }
}
