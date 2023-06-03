<?php

namespace App\job;

use App\Http\Resources\chat\showChatResource;
use App\Models\ChatNotification;
use App\Models\RoomMember;
use App\Models\UserChat;
use App\Traits\Firebase;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use phpDocumentor\Reflection\Types\Boolean;

class NotSeenNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels,Firebase;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {


        $UserChat=  UserChat::where("status",1)->get();

        foreach ($UserChat as $chat){


            if(!$this->check_is_notin_chat_not($chat->id)) {

                if($chat->created_at != null){

                    if ($this->checkTime($chat->created_at)) {


                        $text =$this->getText($chat);
                        $image=$this->checkMedia($chat);
                        $token=$this->getToken($chat->room_id,$chat->user_id);
                        $body = $this->getBody($image,$text);

                        if($token !=null){
                            $this->sendFirebaseNotificationCustom(["title"=>"you have new message","body"=>$body],[$token]);
                            $this->addOnchatNot($chat->id);

                        }
                    }
                }
            }
        }
    }

    public function checkTime($time){
       return Carbon::parse($time)
            ->addSeconds(10)
            ->format('Y-m-d H:i:s') < now();
    }

    public function getToken($room_id ,$user_id){
        $member =RoomMember::where([["room_id",$room_id],["user_id","!=",$user_id]])->first();
        $token= $member != null ?$member->users->device_token:null;
        return $token;
    }

    public function addOnchatNot($chat_id){
        $chatNot = new ChatNotification();
        $chatNot->chat_id=$chat_id;
        $chatNot->save();
    }

    public function check_is_notin_chat_not($chat_id){
       return ChatNotification::where("chat_id",$chat_id)->exists();
    }

    public function getText($chat){
        $text="";
        if($chat->message_chat != null){
            if($chat->message_chat->messageText != null){
                $text=$chat->message_chat->messageText->Text;
            }
        }
        return $text;
    }

    public function checkMedia($chat){
        $media=[];
        if($chat->message_chat){
            if($chat->message_chat->getFirstMediaFile('Message') != null){
                foreach ($chat->message_chat->getFirstMediaFile('Message') as $message_media){
                    $media[] = [$message_media->url, intval($message_media->mediaType->media_type)];
                }
            }
        }
      return  $media !=null;
    }
    public function getBody($image,$text){
        $body='';
        $body.=$image == true ? '🌉':'';
        $body.=$text != null ? $text :'';
        return $body;
    }


}
