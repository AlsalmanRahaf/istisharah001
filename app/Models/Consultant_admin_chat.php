<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultant_admin_chat extends Model
{
    use HasFactory;

    /**
     * @var mixed
     */

    /**
     * @var mixed
     */

    protected $fillable = [
        'admin_id', 'consultant_id'
    ];

    public  function room(){
        return $this->belongsTo(Room::class,"room_id");
    }

}
