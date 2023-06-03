<?php

namespace App\Models;

use App\Helpers\Media\Src\IMedia;
use App\Helpers\Media\Src\MediaGroups;
use App\Helpers\Media\Src\MediaInitialization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model implements IMedia
{
    use HasFactory,MediaInitialization;

    protected $fillable = [
        'type', 'status','user_chat_id'
    ];

    public  function user_chat(){
        return $this->belongsTo(UserChat::class,"user_chat_id");
    }

    public  function messageText(){
        return $this->hasOne(MessageText::class,"message_id");
    }
    const IMAGE_PATH = "Message";


    public function setMainDirectoryPath(): string
    {
        return self::IMAGE_PATH;
    }

    public function setGroups(): MediaGroups
    {
        return (new MediaGroups())->setGroup("multi", "Message", DS);
    }
}
