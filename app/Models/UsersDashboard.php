<?php

namespace App\Models;


use App\Helpers\Media\Src\MediaGroups;
use Illuminate\Database\Eloquent\Model;
use App\Models\Role;
use App\Helpers\Media\Src\MediaInitialization;
use App\Helpers\Media\Src\IMedia;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;  //add the namespace


class UsersDashboard extends Authenticatable
{
    use HasApiTokens, Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'full_name', 'email', 'password', 'active', 'profile_photo','token', 'role_id', 'is_admin'
    ];
    protected $appends = ["directory_path", "photo_path"];
    protected $table="users_dashboards";

//    const IMAGE_PATH = "UserProfile";
//    public function setMainDirectoryPath(): string
//    {
//        return self::IMAGE_PATH;
//    }
//    public function setGroups(): MediaGroups
//    {
//        return (new MediaGroups())->setGroup("single", "Doctors", DS);
//    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setPasswordAttribute($value){
        $this->attributes["password"] = Hash::make($value);
    }

    public function getPhotoPathAttribute(){
        return env("APP_PATH") . "public/uploads/profile/" . (!empty($this->profile_photo) ? $this->profile_photo : "default.jpg");
    }

    public function getDirectoryPathAttribute(){
        return "uploads" . DS . "profile" . DS ;
    }
    public function role(){
        return $this->belongsTo(Role::class, "role_id", "id");
    }
}
