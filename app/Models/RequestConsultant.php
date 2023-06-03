<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestConsultant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id'
    ];
    public  function users(){
        return $this->belongsTo(User::class,"user_id");
    }

    public function requestChat()
    {
        return $this->hasOne(RequestConsultantChat::class,"request_id");
    }
}
