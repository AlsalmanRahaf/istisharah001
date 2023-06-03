<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageText extends Model
{
    use HasFactory;
    protected $fillable = [
        'message_id', 'Text'
    ];

    public  function message(){
        return $this->belongsTo(Message::class,"message_id");
    }

}

