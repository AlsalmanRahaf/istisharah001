<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class ButtonSetting extends Model
{
    use HasFactory;
    protected $fillable = ["type","status", "title_en", "title_ar", "description_en", "description_ar"];
    protected $hidden = ["title_en", "title_ar","description_en", "description_ar"];
    protected $appends = ["title","description"];

    //Attributes
    public function getTitleAttribute(){
        return $this->{"title_" . App::getLocale()};
    }
    public function getDescriptionAttribute(){
        return $this->{"description_" . App::getLocale()};
    }

}
