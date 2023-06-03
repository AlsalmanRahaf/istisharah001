<?php

namespace App\Http\Resources\Consultant;

use App\Models\Consultant_admin_chat;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class showConsultant extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {


        $data=[];

        if(in_array($this->type,["c","cph","cn","cd"])){
            $data= [
                "id"=>$this->id,
                "full_name" => $this->getName($this->full_name,$this->type),
                "image" => $this->getFirstMediaFile('profile_photo')->url ?? "",
                "status"=>$this->status,
                "switch_status"=>$this->switch_status,
                "admin_status_chat" => $this->haveChatAdmin($this->id),
            ];
        }

            if($this->type == "u" && $this->custom_consultations != null){
                $data= [
                    "id"=>$this->id,
                    "full_name" => $this->custom_consultations->consultation_name,
                    "image" => $this->getFirstMediaFile('profile_photo')->url ?? "",
                    "status"=>$this->status,
                    "switch_status"=>$this->switch_status,
                    "admin_status_chat" => $this->haveChatAdmin($this->id),
                ];
            }

        return $data;


    }

    public function getName($name,$type){
        $lang= App::getLocale();
        switch ($type){
            case "c":
                $name=$name;
                break;
            case "cph":
                $name=$lang == "en" ? "pharmacist specialist" :"صيدلي";
                break;
            case "cn":
                $name=$lang == "en" ? "nutrition specialist":"اخصائي تغذية";
                break;
            case "cd":
                $name=$lang == "en" ?"Diabetes specialist":"أخصائي سكري";
                break;
        }
        return $name;
    }

    public function haveChatAdmin($consultant_id){
        $chat_admin_consultants=Consultant_admin_chat::where("consultant_id",$consultant_id)->first();
        if($chat_admin_consultants){
                if($chat_admin_consultants->room != null && $chat_admin_consultants->room->user_chat != null){
                    $user_chats =$chat_admin_consultants->room->user_chat;
                    foreach ($user_chats as $chat){
                        if($chat->user_id != $consultant_id && $chat->status == 1){
                            return 1;
                        }
                    }
                }
        }
        return 0;
    }
}
