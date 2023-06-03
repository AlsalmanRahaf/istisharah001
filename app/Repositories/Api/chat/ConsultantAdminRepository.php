<?php

namespace App\Repositories\Api\chat;

use App\Models\BlockConsultantConsultation;
use App\Models\Consultant_admin_chat;
use App\Models\consultation;
use App\Models\RequestConsultant;
use App\Models\RequestConsultation;
use App\Models\User;
use App\Traits\Helper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Room;
use App\Models\RoomMember;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;


class ConsultantAdminRepository
{
  use Helper;

    public  function  Rules(){
        return [
            "type" => [Rule::in(["c","a"])],
            "consultant_id" => ["required_if:type,==,a"],
        ];
    }

    public function check_chat_exists($consultent_id,$admin_id=""){

        if($admin_id == ""){
            $check=Consultant_admin_chat::where(["consultant_id"=>$consultent_id])->exists();
        }else{
            $check=Consultant_admin_chat::where(["admin_id"=>$admin_id ,"consultant_id"=>$consultent_id])->exists();
        }
        return $check;
    }

    public  function block_consultant_consultation($request){
        if(Auth::user()->type == 'a'){

            $admin_id=Auth::user()->id;
            $consultation_id=$request->consultant_id;
            $type=$request->type;
            $status=$request->status;


            if($status == 1){
                $consultation=consultation::find($consultation_id);
                $consultation->status=3;
                if($consultation->save()){
                    if(!BlockConsultantConsultation::where("consultation_id",$consultation_id)->exists()){
                        $block = new BlockConsultantConsultation();
                        $block->consultation_id=$consultation_id;
                        $block->admin_id=$admin_id;
                        $block->type=$type;
                        if($block->save()){
                            $consultant_id=$consultation->consultant_id;
                            $url=env("NODEJSURL").'/block_consultant_consultation';
                            $this->sendRequest('post',[
                                'consultant_id' => $consultant_id,
                            ],$url);
                        }
                    }
                }
            }else{
                $consultation=consultation::find($consultation_id);
                $consultation->status=2;
                $consultation->save();
                BlockConsultantConsultation::where("consultation_id",$consultation_id)->delete();
            }
            return true;
        }
        return false;
    }

    public function  check_admin_exists(){
        return User::where("type","a")->exists();
    }

    public function create_new_room($admin,$consultent_id){
        $newRoom=new Room();
        $newRoom->room_id=Str::random(30);
        $newRoom->chat_type=3;
        if($newRoom->save()){
            $Consultant_admin_chat = new Consultant_admin_chat();
            $Consultant_admin_chat->admin_id=$admin;
            $Consultant_admin_chat->consultant_id=$consultent_id;
            $Consultant_admin_chat->room_id=$newRoom->id;
            if($Consultant_admin_chat->save()){
                $this->addMember($admin,$newRoom->id);
                $this->addMember($consultent_id,$newRoom->id);
            }
        }
        return $newRoom->room_id;
    }
    public function get_room_id($consultent_id){
       return Consultant_admin_chat::join("rooms","consultant_admin_chats.room_id","=","rooms.id")
           ->where("consultant_admin_chats.consultant_id",$consultent_id)
           ->first("rooms.room_id");
    }


    public function create_room($request){

        // auth can be consultent or admin depend on  type
        $user=Auth::user();
        $Data=[];

       if($request->type == "a"){

           $consultent=User::find($request->consultant_id);


           if($user->type == "a" && $consultent->type == "c"){

               if($this->check_chat_exists($consultent->id)){

                   $Data= $this->get_room_id($consultent->id);

               }else{
                   $Data["room_id"]=$this->create_new_room($user->id,$consultent->id);


               }

           }else{
                $Data["message"]="you are not admin";
           }
           return $Data;
       }elseif($request->type == "c"){

           if($this->check_admin_exists()){
               if($this->check_chat_exists($user->id)){
//                   $admin=Consultant_admin_chat::where(["consultant_id"=>$user->id])->get();
                   $Data=$this->get_room_id($user->id);
               }else{
                   $admin = User::where([["type","a"],["status",1]])->first();
                   $Data["room_id"]= $this->create_new_room($admin->id,$user->id);
               }

           }else{
               $Data["message"]="admin is not available";
           }
        return $Data;
       }

    }
    protected  function addMember($user_id,$room_id){

        $registerMember=new RoomMember;
        $registerMember->user_id= $user_id;
        $registerMember->room_id= $room_id;
        $registerMember->save();

    }
}
