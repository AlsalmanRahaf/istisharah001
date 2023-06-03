<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObjectDetails extends Model
{
    use HasFactory;

    public function time_slot_types(){
        return $this->belongsTo(TimeSlotType::class,'time_slot_type_id','id');
    }
}
