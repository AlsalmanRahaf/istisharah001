<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockedUsers extends Model
{
    use HasFactory;

    public function blocked_users(){
        return $this->belongsTo(User::class,"block_id");
    }
}
