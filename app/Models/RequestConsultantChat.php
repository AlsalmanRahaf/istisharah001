<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestConsultantChat extends Model
{
    use HasFactory;

    public function RequestConsultant(){
        return $this->belongsTo(RequestConsultant::class,"request_id");
    }
    public function room(){
        return $this->belongsTo(Room::class,"room_id");
    }
}
