<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorUserRate extends Model
{
    use HasFactory;

    protected $fillable=["rated_id","rate","note","rateable_type","rateable_id"];

    public function rateable(){
        return $this->morphTo();
    }

}
