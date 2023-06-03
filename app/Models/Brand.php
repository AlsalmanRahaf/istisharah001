<?php

namespace App\Models;

use App\Helpers\Media\Src\MediaInitialization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Brand extends Model
{
    use HasFactory, MediaInitialization;

    public function setMainDirectoryPath(): string
    {
        return "brands";
    }

    public function getNameAttribute(){
        return $this->{"name_" . App::getLocale()};
    }

    public function getDescriptionAttribute(){
        return $this->{"description_" . App::getLocale()};
    }

    public function category(){
        return $this->belongsTo(Category::class, "category_id");
    }

}
