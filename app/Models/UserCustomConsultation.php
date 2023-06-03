<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCustomConsultation extends Model
{
    use HasFactory;

    public  function users(){
        return $this->belongsTo(User::class,"user_id");
    }

    public  function room(){
        return $this->belongsTo(Room::class,"room_id");
    }

    public function custom_consultations(){
        return $this->belongsTo(CustomConsultation::class,"custom_consultation_id");
    }
}
