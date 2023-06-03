<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Media\Src\IMedia;
use App\Helpers\Media\Src\MediaGroups;
use App\Helpers\Media\Src\MediaInitialization;


class Ads extends Model implements IMedia
{
    use HasFactory,MediaInitialization;

    protected $fillable = ["status", "type"];


    const IMAGE_PATH = "Ads";


    public function setMainDirectoryPath(): string
    {
        return self::IMAGE_PATH;
    }

    public function setGroups(): MediaGroups
    {
        return (new MediaGroups())->setGroup("multi", "Ads", DS)->setGroup("single", "Logo", DS);
    }

    public  function Ads_text(){
        return $this->hasOne(Ads_text::class, "ads_id");
    }

}
