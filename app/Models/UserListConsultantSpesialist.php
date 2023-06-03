<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserListConsultantSpesialist extends Model
{
    use HasFactory;
    public  function SpesialistConsultant(){
        return $this->belongsTo(User::class,"consultant_id");
    }
    public  function room(){
        return $this->belongsTo(Room::class,"room_id");
    }
}
