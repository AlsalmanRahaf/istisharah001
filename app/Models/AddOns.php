<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class AddOns extends Model
{
    use HasFactory;

    public function getNameAttribute(){
        return $this->{"name_" . App::getLocale()};
    }

    public function options(){
        return $this->hasMany(AddOnsOption::class, "add_on_id");
    }
}
