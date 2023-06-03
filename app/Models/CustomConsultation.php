<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class CustomConsultation extends Model
{
    use HasFactory;
    protected $fillable = ['consultation_name_en','consultation_name_ar', 'consultant_id','status'];
    protected $hidden = ["consultation_name_en", "consultation_name_ar"];
    protected $appends = ["consultation_name"];

    public function getConsultationNameAttribute(){
        return $this->{"consultation_name_" . App::getLocale()};
    }
    public  function consultant(){
        return $this->belongsTo(User::class,"consultant_id");
    }
    public function getConsultationType(){
        return "consultation_name_" . App::getLocale();
    }


}
