<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserChat extends Model
{
    use HasFactory;
    protected $fillable = [
        'status', 'user_id','room_id'
    ];

    public  function room(){
        return $this->belongsTo(room::class,"room_id");
    }

    public function message_chat(){
        return $this->hasOne(Message::class,"user_chat_id");
    }

    public  function users(){
        return $this->belongsTo(User::class,"user_id");
    }

    public function last(){
        return $this->latest()->first();
    }


}
