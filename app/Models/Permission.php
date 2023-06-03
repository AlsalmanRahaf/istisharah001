<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;
    protected $fillable = ["name","slug"];
    protected $table = "admin_permissions";

    public function roles(){
        return $this->belongsToMany(Role::class, "admin_role_permission", "permission_id", "role_id");
    }
}
