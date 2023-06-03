<?php

namespace App\Http\Resources\Consultation;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class                           ShowReConsultationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "full_name" => $this->users->full_name,
            "consultation_id" =>$this->consultation_id,
            "image" => $this->users->getFirstMediaFile('profile_photo')->url ?? "",
            "message"=>$this->getMessage(),
            "chat_id"=>$this->getChatId(),
            "chat_status"=>$this->getChatStatus(),
            "room_id"=>$this->room->room_id,
            "user_id"=>$this->users->id,
            "age"=>$this->users->age,
            "status"=>$this->status
        ];

    }
    public function getMessage(){
        if($this->room->user_chat == null || $this->room->user_chat->last() ==null || $this->room->user_chat->last()->message_chat->messageText == null){
            $message = "";
        }else{
            $message= $this->room->user_chat->last()->message_chat->messageText->Text;
        }
        return $message;
    }
    public function getChatId(){


        if($this->room->user_chat == null || $this->room->user_chat->last() ==null){
        $id=null;
        }else{
        $id=$this->room->user_chat->last()->id;
        }
        return $id;
    }
    public function getChatStatus(){
        if($this->room->user_chat == null || $this->room->user_chat->last() == null) {
            return null;
        }else{
            return $this->room->user_chat->last()->status;
        }
    }
}
