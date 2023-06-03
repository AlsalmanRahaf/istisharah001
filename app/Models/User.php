<?php

namespace App\Models;

use App\Helpers\Media\Src\IMedia;
use App\Helpers\Media\Src\MediaGroups;
use App\Helpers\Media\Src\MediaInitialization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\SelectBuilder\Builders\User as UserSelectBuilder;

class User extends Authenticatable implements IMedia
{
    use  HasFactory, Notifiable, HasApiTokens, MediaInitialization;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id','full_name', 'phone_number','type','firebase_uid','email','description','country','date_of_birth','switch_status','device_token','country_code','notification_lang'
    ];

    protected $hidden = [
        'password', 'remember_token','created_at','updated_at'
    ];



    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */


    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    const IMAGE_PATH = "users";
    public static function selectBuilder(): UserSelectBuilder
    {return new UserSelectBuilder();}

    public function setMainDirectoryPath(): string
    {
        return self::IMAGE_PATH;
    }

    public function setGroups(): MediaGroups
    {
        return (new MediaGroups())->setGroup("single", "profile_photo", DS)
            ->setGroup("multi", "attach_photo", DS);
    }


    public function RequestConsultation(){
       return  $this->hasMany(RequestConsultant::class,"user_id");
    }
    public function RequestConsultant(){
        return  $this->hasMany(RequestConsultant::class,"user_id");
    }

    public function ReferredConsultation(){
        return  $this->hasMany(ReferredConsultation::class,"user_id");
    }

    public function UsesPromoCode(){
        return  $this->hasMany(UsesPromoCode::class,"user_id");
    }

    public function PromoCode(){
        return  $this->hasMany(PromoCode::class,"user_id");
    }

    public function RoomMember(){
        return  $this->hasMany(RoomMember::class,"user_id");
    }
    public function user_chat(){
        return  $this->hasMany(UserChat::class,"user_id");
    }

    public function custom_consultations(){
        return  $this->hasOne(CustomConsultation::class,"consultant_id");
    }
    public function user_booking(){
        return  $this->hasMany(UserBookings::class,"user_id","id");
    }
    public function user_device_token(){
        return $this->hasMany(UserDeviceToken::class,"user_id");
    }
    public function doctor(){
        return $this->hasOne(Doctor::class,'user_id','id');
    }
    public function rateable(){
        return$this->morphMany(DoctorUserRate::class,"rateable");
    }
    public function cancelledBooking(){
        return $this->morphMany(CancelledBooking::class,"cancelledBooking","user_type","user_id");
    }
}
