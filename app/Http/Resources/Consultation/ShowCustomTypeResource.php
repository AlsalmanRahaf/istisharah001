<?php

namespace App\Http\Resources\Consultation;

use App\Models\Room;
use App\Models\UserCustomConsultation;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ShowCustomTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $consultant_id=$this->consultant_id;
        $data = [
            "type" => $this->getType(),
            "consultant_id" => $consultant_id,
            "has_message" =>  $this->has_chat($this->id),
        ];
        return $data;
    }
    public function getType(){
        $type=$this->getConsultationType();
        return $this->$type;
    }

    public function has_chat($custom_consultation_id){
        $chat=0;
        $user_id= Auth::user()->id;
        $room_id=UserCustomConsultation::where([["custom_consultation_id",$custom_consultation_id],["user_id",$user_id]])->first("room_id");
        if($room_id){
            $user_chat=Room::find($room_id)->first()->user_chat;
            foreach ($user_chat as $message){
                if($message->user_id != $user_id && $message->status == 1){
                    $chat=1;
                    break;
                }
            }
        }
        return $chat;
    }
}
