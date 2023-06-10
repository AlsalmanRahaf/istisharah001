<?php

namespace App\Models;

use App\Helpers\Media\Src\IMedia;
use App\Helpers\Media\Src\MediaInitialization;
use App\Helpers\Media\Src\MediaGroups;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultantRequest extends Model implements IMedia
{
    use HasFactory, MediaInitialization;

    const IMAGE_PATH = "Consultant";

    protected $fillable = [
        'name', 'phone', 'email', 'description', 'online_price', 'offline_price', 'request_type', 'chat_price'
    ];

    public function setMainDirectoryPath(): string
    {
        return self::IMAGE_PATH;
    }


    public function setGroups(): MediaGroups
    {
        return (new MediaGroups())
            ->setGroup("single", "Consultant", DS)
            ->setGroup("single", "Consultant Photo", DS)
            ->setGroup("multi", "Consultant Documents", DS);
    }

}
