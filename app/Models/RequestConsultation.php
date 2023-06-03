<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestConsultation extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'room_id','status'
    ];

    public  function users(){
        return $this->belongsTo(User::class,"user_id");
    }

    public  function room(){
        return $this->belongsTo(Room::class,"room_id");
    }

}
