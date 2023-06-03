<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeSlotType extends Model
{
    use HasFactory;

    public function object_details(){
        return $this->hasOne(ObjectDetails::class,'time_slot_type_id','id');
    }

    public function object_week_days() {
        return $this->hasMany(ObjectWeekDays::class,'time_slot_type_id','id');
    }
}
