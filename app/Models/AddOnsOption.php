<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class AddOnsOption extends Model
{
    use HasFactory;
    protected $fillable = ["add_on_id","name_en", "name_ar", "price"];

    public function getNameAttribute(){
        return $this->{"name_" . App::getLocale()};
    }
}
