<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class consultation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'consultant_id','room_id','status','consultations_status'
    ];

    public  function room(){
        return $this->belongsTo(Room::class,"room_id");
    }


    public  function users(){
        return $this->belongsTo(User::class,"user_id");
    }

    public  function consultant(){
        return $this->belongsTo(User::class,"consultant_id");
    }
}
