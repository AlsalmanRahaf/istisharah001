<?php

namespace App\Models;

use App\Helpers\Media\Src\IMedia;
use App\Helpers\Media\Src\MediaInitialization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Category extends Model implements IMedia
{
    use HasFactory, MediaInitialization;

    public function setMainDirectoryPath(): string
    {
        return "categories";
    }

    public function getNameAttribute(){
        return $this->{"name_" . App::getLocale()};
    }
}
