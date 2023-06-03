<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsesPromoCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'promo_code_id'
    ];

    public  function UsesPromoCode(){
        return $this->belongsTo(PromoCode::class,"promo_code_id");
    }
    public  function users(){
        return $this->belongsTo(User::class,"user_id");
    }

}
