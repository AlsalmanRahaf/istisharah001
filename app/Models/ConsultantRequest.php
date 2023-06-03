<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultantRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'phone', 'email', 'description', 'online_price', 'offline_price','request_type'
    ];
}
