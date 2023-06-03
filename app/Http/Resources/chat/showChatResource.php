<?php

namespace App\Http\Resources\chat;

use App\Models\VipChat;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class showChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $user_type=Auth::user()->type;
        $media=[];
        if($this->message_chat){
            if($this->message_chat->getFirstMediaFile('Message') != null){
                foreach ($this->message_chat->getFirstMediaFile('Message') as $message_media){
                           $media[] = [$message_media->url, intval($message_media->mediaType->media_type)];
                }
            }
        }
        $text="";
        if($this->message_chat != null){
            if($this->message_chat->messageText != null){
                $text=$this->message_chat->messageText->Text;
            }
        }
        $data= [
            "chat_id"=>$this->id,
            "status"=>$this->status,
            "text" => $text,
            "room_id" => $this->room_id,
            "type" => $this->message_chat != null ? intval($this->message_chat->type):null,
            "direction" => $this->user_id  == Auth::user()->id ? "sender" : "receiver",
            "media" =>  $media,
            "user_id"=>$this->user_id,
            "created_at"=>Carbon::parse($this->created_at->timezone('Asia/Amman')->toDateTimeString())->format('d-m-y'),
            "time"=>Carbon::parse($this->created_at->timezone('Asia/Amman')->toDateTimeString())->format('H:i'),
        ];


        if($user_type=="u" && $this->status == 0){
            $data=null;
        }

        if($this->check_if_vip($this->id) && !($user_type == "u" || $user_type == "a")){
            $data=null;
        }
        return  $data;
    }

    public function check_if_vip($id){
       return VipChat::where("chat_id",$id)->exists();
    }
}
