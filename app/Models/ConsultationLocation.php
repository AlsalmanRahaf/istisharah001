<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultationLocation extends Model
{
    use HasFactory;
    protected $fillable = [
        'room_id', 'type','country','location','consultations_status'
    ];

}
