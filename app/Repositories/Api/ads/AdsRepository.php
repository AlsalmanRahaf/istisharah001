<?php

namespace App\Repositories\Api\ads;


use App\Models\Room;
use App\Models\RoomMember;
use App\Models\SupportAds;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdsRepository
{

    public function getAvilapelAdmin(){
        if(User::where([["type","a"],["status",1]])->exists()){
            return User::where([["type","a"],["status",1]])->first("id");
        }
        return false;
    }
    public function create_room($user_ids){
        $newRoom=new Room();
        $newRoom->room_id=Str::random(30);
        if($newRoom->save()){
            $room_id=$newRoom->id;
            foreach ($user_ids  as $user_id){
                $registerMember=new RoomMember;
                $registerMember->user_id= $user_id;
                $registerMember->room_id= $room_id;
                $registerMember->save();
            }
        }
        return ["id"=>$newRoom->id,"room_id"=>$newRoom->room_id];
    }

    public function check_room_exists($user_id,$admin_id){
      return  SupportAds::where([["user_id",$user_id],["admin_id",$admin_id],["status",1]])->exists();
    }

    public function get_room_id($id){
        return Room::find($id)->room_id;
    }


    public function create_support_ads(){

        $data=[];
        if($this->getAvilapelAdmin()){
            $admin=$this->getAvilapelAdmin();
            $user_id=Auth::user()->id;


            if($this->check_room_exists($user_id,$admin->id)){
                $room_id= SupportAds::where([["user_id",$user_id],["admin_id",$admin->id],["status",1]])->first("room_id")->room_id;
                $data["room_id"]=$this->get_room_id($room_id);
            }else{
                $room_id=$this->create_room([$admin->id,$user_id]);
                $support=new SupportAds;
                $support->user_id=$user_id;
                $support->admin_id=$admin->id;
                $support->room_id=$room_id["id"];
                $support->status=1;
                $support->save();
                $data["room_id"]=$room_id["room_id"];
            }
        }
        return $data;
    }
    public function getSupportAdsList(){
        return SupportAds::all();
    }
}
