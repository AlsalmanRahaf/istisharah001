<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingRating extends Model
{
    use HasFactory;
    protected $fillable = ["rated_by","rated_doctor", "user_booking_id", "rating_value", "notes"];
}
