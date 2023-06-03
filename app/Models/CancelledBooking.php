<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CancelledBooking extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function cancelledBooking(){
        return $this->morphTo();
    }
}
