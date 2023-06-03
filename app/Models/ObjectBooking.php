<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObjectBooking extends Model
{
    use HasFactory;

    public function time_slots(){
        return $this->hasOne(TimeSlot::class,'slot_id','id');
    }
}
