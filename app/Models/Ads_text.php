<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ads_text extends Model
{
    use HasFactory;
    protected $fillable = ["ads_id", "Data","background_color"];

    public  function Ads(){
        return $this->belongsTo(Ads::class, "ads_id");
    }
}
