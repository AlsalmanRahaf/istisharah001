<?php

namespace App\Models;

use App\Helpers\Media\Src\IMedia;
use App\Helpers\Media\Src\MediaDefaultPhotos;
use App\Helpers\Media\Src\MediaInitialization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;  //add the namespace


class Admin extends Authenticatable implements IMedia
{
    use HasFactory, MediaInitialization, MediaDefaultPhotos  , HasApiTokens;
    protected $fillable = ["full_name", "username", "email", 'password', 'active', 'profile_photo','token', 'role_id', "is_super_admin","status"];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $appends = ["directory_path", "photo_path"];


    /**
     * @throws \Exception
     */
    public function getProfilePhotoAttribute(){
        return $this->getFirstMediaFile() ? $this->getFirstMediaFile()->url : self::defaultUserPhoto();
    }

    public function setMainDirectoryPath(): string
    {
        return "admins";
    }


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
    public function cancelledBooking(){
        return $this->morphMany(CancelledBooking::class,"cancelledBooking","user_type","user_id","id");
    }
    public function role(){
        return $this->belongsTo(Role::class, "role_id");
    }
}
