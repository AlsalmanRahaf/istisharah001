<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    use HasFactory;
    public function object_booking(){
        return $this->belongsTo(ObjectBooking::class,'slot_id','id');
    }

    public function object_details(){
        return $this->hasOne(ObjectDetails::class,'time_slot_type_id','id');
    }

    public function object_week(){
        return $this->belongsTo(ObjectWeekDays::class,'object_week_days_id','id');
    }
}
