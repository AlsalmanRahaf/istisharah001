<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ["id","name"];
    protected $table = "admin_roles";
    public $timestamps = false;

    public function permissions(){
        return $this->belongsToMany(Permission::class, "admin_role_permission", "role_id", "permission_id");
    }

    public function users(){
        return $this->hasMany(Admin::class, "role_id", "id");
    }
}
