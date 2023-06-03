<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\chat\showChatResource;
use App\Http\Resources\web\MessagesResource;
use App\Models\Message;
use App\Models\Room;
use App\Models\UserChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function get_room_by_type($type){
        return Room::where("chat_type",$type)->orderBy('id', 'DESC')->get();
    }

    public function get_consultation_rooms_by_type(){
        $data=Room::where("chat_type",2)->orderBy('id', 'DESC')->get();
        return $data;
    }

    public function admin()
    {
        //
        if(Room::where("chat_type",3)->exists()){
            $Data["rooms"]=$this->getChat(3);
        }else{
            $Data["rooms"]=[];
        }
        return view("admin.messages.admin",$Data);
    }

    public function getCustomConsultationData(){
        $Data["rooms"]=$this->get_other_consultation_chat();
        return view("admin.messages.CustomConsultant",$Data);
    }

    public function consultant()
    {
        //
        if(Room::where("chat_type",2)->exists()){
            $Data["rooms"] =$this->getConsultationData(1);
        }else{
            $Data["rooms"]=[];
        }
        return view("admin.messages.consultant",$Data);
    }

    public function specializedconsultant()
    {
        //
        if(Room::where("chat_type",2)->exists()){
            $Data["rooms"] =$this->getConsultationData(0);
        }else{
            $Data["rooms"]=[];
        }
        return view("admin.messages.specializedConsultant",$Data);
    }

    public function users()
    {
        //
        if(Room::where("chat_type",1)->exists()){
            $Data["rooms"]=$this->getChat(1);
        }else{
            $Data["rooms"]=[];
        }
        return view("admin.messages.users",$Data);
    }


    public function getChat($type){
        $Roomchat=[];
        $All_Rooms=$this->get_room_by_type($type);

        foreach ($All_Rooms as $key => $r){
            $Allchat=[];
            $first_user=$r->user_chat->first()!=null ? $r->user_chat->first()->user_id:null;

            if(isset($r->user_chat)){
                foreach ($r->user_chat as $chat){
                    if($chat->message_chat != null){
                        if($chat->message_chat->messageText != null){
                            $Roomchat["text"]=$chat->message_chat->messageText->Text;
                        }
                    }
                    $media=[];
                    if($chat->message_chat){
                        if($chat->message_chat->getFirstMediaFile('Message') != null){
                            foreach ($chat->message_chat->getFirstMediaFile('Message') as $message_media){
                                $media[] = [$message_media->url, intval($message_media->mediaType->media_type)];
                            }
                        }
                    }


                    $Roomchat["room_id"] = $chat->room_id;
                    $Roomchat["type"] = $chat->message_chat != null ? intval($chat->message_chat->type):null;
                    $Roomchat["direction"] = $chat->user_id  == $first_user ? "sender" : "receiver";
                    $Roomchat["media"] =  $media;
                    $Roomchat["user_name"] =  $chat->users->phone_number;
                    $Roomchat["created_at"] =$chat->message_chat != null ?$chat->message_chat->created_at->diffForHumans():"2022-01-30 14:19:58";

                    $Allchat[]= $Roomchat;
                }
            }
            $All_Rooms[$key]["chat"]= $Allchat;
        }

        return $All_Rooms;
    }


    public function get_other_consultation_chat() {
        $all=Room::where("chat_type",2)->orderBy('id', 'DESC')->get();
        $rooms=[];

        foreach ($all as $room){

            if($room->UserCustomConsultation != null){

                //for empty messages
                $messages=[];
                $first_user=$room->user_chat->first()!=null ? $room->user_chat->first()->user_id:null;
                foreach ($room->user_chat as $chat){
                    if($chat->message_chat != null){
                        if($chat->message_chat->messageText != null){
                            $Roomchat["text"]=$chat->message_chat->messageText->Text;
                        }
                    }
                    $media=[];
                    if($chat->message_chat){
                        if($chat->message_chat->getFirstMediaFile('Message') != null){
                            foreach ($chat->message_chat->getFirstMediaFile('Message') as $message_media){
                                $media[] = [$message_media->url, intval($message_media->mediaType->media_type)];
                            }
                        }
                    }
                    $Roomchat["room_id"] = $chat->room_id;
                    $Roomchat["type"] = $chat->message_chat != null ? intval($chat->message_chat->type):null;
                    $Roomchat["direction"] = $chat->user_id  == $first_user ? "sender" : "receiver";
                    $Roomchat["media"] =  $media;
                    $Roomchat["user_name"] =  $chat->users->full_name !=null ?$chat->users->full_name:$chat->users->phone_number;
                    $Roomchat["created_at"] =$chat->message_chat != null ?$chat->message_chat->created_at->diffForHumans():"2022-01-30 14:19:58";
                    $messages[]=$Roomchat;
                }
                $room->chat=$messages;
                $rooms[]=$room;
            }
        }
        return $rooms;
    }

    public  function  getConsultationData($type){
        $all= $this->get_consultation_rooms_by_type();
        $rooms=[];

        foreach ($all as $room){

            $chattype = $type == 1 ?$room->consultation:$room->ReferredConsultation;
            if( $chattype != null){
            //for empty messages
            $messages=[];
            $first_user=$room->user_chat->first()!=null ? $room->user_chat->first()->user_id:null;
            foreach ($room->user_chat as $chat){
                if($chat->message_chat != null){
                    if($chat->message_chat->messageText != null){
                        $Roomchat["text"]=$chat->message_chat->messageText->Text;
                    }
                }
                $media=[];
                if($chat->message_chat){
                    if($chat->message_chat->getFirstMediaFile('Message') != null){
                        foreach ($chat->message_chat->getFirstMediaFile('Message') as $message_media){
                            $media[] = [$message_media->url, intval($message_media->mediaType->media_type)];
                        }
                    }
                }
                $Roomchat["room_id"] = $chat->room_id;
                $Roomchat["type"] = $chat->message_chat != null ? intval($chat->message_chat->type):null;
                $Roomchat["direction"] = $chat->user_id  == $first_user ? "sender" : "receiver";
                $Roomchat["media"] =  $media;
                $Roomchat["user_name"] =  $chat->users->full_name !=null ?$chat->users->full_name:$chat->users->phone_number;
                $Roomchat["created_at"] =$chat->message_chat != null ?$chat->message_chat->created_at->diffForHumans():"2022-01-30 14:19:58";
                $messages[]=$Roomchat;
            }
                $room->chat=$messages;
                $rooms[]=$room;
        }
        }
       return $rooms;
    }



}
