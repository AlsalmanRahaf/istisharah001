<?php

namespace App\Models;

use App\Helpers\Media\Src\IMedia;
use App\Helpers\Media\Src\MediaGroups;
use App\Helpers\Media\Src\MediaInitialization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultant extends Model implements IMedia
{
    use HasFactory,MediaInitialization;

    protected $fillable = ["full_name", "phone_number", "email","has_zoom", "specialization_id", "user_id", "object_id", "payment_methods", "description"];

    const IMAGE_PATH = "Doctors";
    public function setMainDirectoryPath(): string
    {
        return self::IMAGE_PATH;
    }


    public function setGroups(): MediaGroups
    {
        return (new MediaGroups())->setGroup("single", "Doctors", DS);
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function rateable(){
        return $this->morphMany(DoctorUserRate::class,"rateable","rateable_type","rateable_id");
    }
}
