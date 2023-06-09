<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomMember extends Model
{
    use HasFactory;
    public  function room(){
        return $this->belongsTo(Room::class,"room_id");
    }
    public  function users(){
        return $this->belongsTo(User::class,"user_id");
    }
}
