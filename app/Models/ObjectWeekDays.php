<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObjectWeekDays extends Model
{
    use HasFactory;
    public function time_slot_week(){
        return $this->belongsTo(TimeSlot::class,'object_week_days','id');
    }

    public function time_slot_type() {
        return $this->belongsTo(TimeSlotType::class,'time_slot_type_id','id');
    }

    public function time_slot(){
        return $this->hasMany(TimeSlot::class,'object_week_days_id','id');
    }
}
