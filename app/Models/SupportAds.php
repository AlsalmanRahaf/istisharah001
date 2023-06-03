<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportAds extends Model
{
    use HasFactory;

    public function user(){
        return $this->belongsTo(User::class,"user_id");
    }

    public function room(){
        return $this->belongsTo(Room::class,"room_id");
    }

}
