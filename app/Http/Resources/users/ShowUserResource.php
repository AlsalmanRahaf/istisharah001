<?php

namespace App\Http\Resources\users;

use App\Models\CustomConsultation;
use App\Models\Room;
use App\Models\RoomMember;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ShowUserResource extends JsonResource
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
            "id" => $this->id,
            "full_name" => $this->full_name,
            "age" => $this->age,
            "phone_number" => $this->phone_number,
            "country" => $this->country,
            "date_of_birth" => $this->date_of_birth,
            "description" => $this->description,
            "diseases" => $this->diseases,
            "profile_photo" => $this->getFirstMediaFile('profile_photo')->url ?? null,
            "last_message" => $this->get_last_message($this->id),
            "type" => $this->type,
            "custom_type"=>$this->getCustomType($this->id),
            "block"=> $this->status == 2 ? 1 :0,
            "switch_status" =>  $this->switch_status == 0 ? $this->getConsultantType($this->id,$this->type) : "u"
        ];

    }

    public function getConsultantType($user_id,$type){
        if($type == "u") {
            if (CustomConsultation::where("consultant_id", $user_id)->exists()) {
                $type="cs";
            }
        }
        return  $type;
    }
    public function get_last_message($sender_id){
        $receiver_id=Auth::user()->id;
        $data=null;
        $room_id=DB::select("SELECT m1.room_id FROM `room_members` m1 INNER JOIN room_members m2 on m1.room_id=m2.room_id where m1.user_id=$receiver_id and m2.user_id=$sender_id");

       if($room_id){

           $room_id=$room_id[0]->room_id;
           $user_rooms=Room::find($room_id)->user_chat;

           foreach ($user_rooms as $user_room) {
               if ($user_room->user_id != $receiver_id) {

                   $media=[];
                   $text="";
                   $status=0;
                   if($user_room->message_chat != null){
                       if($user_room->message_chat->getFirstMediaFile('Message') != null){
                           foreach ($user_room->message_chat->getFirstMediaFile('Message') as $message_media){
                               $media[] = [$message_media->url, intval($message_media->mediaType->media_type)];
                           }
                       }

                       if($user_room->message_chat->messageText != null){
                           $text=$user_room->message_chat->messageText->Text;
                           $status=$user_room->message_chat->status;
                       }
                   }

                   $data = [
                       "sender"=>$sender_id,
                       "text" => $text,
                       "type" => intval($user_room->message_chat->type),
                       "media" =>  $media,
                       'status' => $status,
                   ];
               }
           }

           }
       return $data;


    }
    public function getCustomType($user_id){
        $check=CustomConsultation::where([["consultant_id",$user_id],["status",1]])->exists();
        $type=null;
        if($check){
            $type=CustomConsultation::where("consultant_id",$user_id)->first();
            $type=$type->consultation_name;
        }
        return $type;
    }
}
