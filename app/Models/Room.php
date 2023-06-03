<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    protected $fillable = [
        'room_id', 'status','type','standard'
    ];

    public  function user_chat(){
        return $this->hasMany(UserChat::class,"room_id");
    }

    public function RequestConsultation(){
        return $this->hasone(RequestConsultation::class,"room_id");
    }


    public function UserCustomConsultation(){
        return $this->hasone(UserCustomConsultation::class,"room_id");
    }

    public function ReferredConsultation(){
        return  $this->hasOne(ReferredConsultation::class,"room_id");
    }
    public function consultation(){
        return $this->hasOne(consultation::class,"room_id");
    }
    public function custom_consultation(){
        return $this->hasOne(UserCustomConsultation::class,"room_id");
    }
    public function room_members(){
        return $this->hasMany(RoomMember::class,"room_id");
    }
    public function UserListConsultantSpesialist(){
        return $this->hasMany(UserListConsultantSpesialist::class,"room_id");
    }

    public function getRequestConsultantChat(){
        return  $this->hasOne(RequestConsultantChat::class,"room_id");

    }

}
