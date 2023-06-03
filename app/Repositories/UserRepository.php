<?php

namespace App\Repositories;


use App\Http\Resources\users\BlockedUsersResource;
use App\Models\BlockedUsers;
use App\Models\CustomConsultation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Traits\Helper;
use Illuminate\Support\Str;
class UserRepository
{
    use Helper;
    public function register($request)
    {
        $user = new User();
        $user->full_name ='user_'.Str::random(3);
        $user->phone_number = $request->phone_number;
        $user->firebase_uid = $request->firebase_uid;
        $user->age = null;
        $user->country = null ;
        $user->type =   "u";
        $user->date_of_birth =  null;
        $user->description = null;
        $user->diseases =  null;
        $user->country_code =  $request->country_code ?? null;
        $user->device_token = $request->device_token ?? null;
        $user->save();
        if($request->hasfile('profile_photo'))
             $user->saveMedia($request->file('profile_photo'),'profile_photo');
        $token = $user->createToken(env("TOKEN_KEY"));
        $tokenObj = $token->token;
        $tokenObj->expires_at = Carbon::now()->addWeeks(4);
        $tokenObj->save();
        $data["user"] = [
            "id" => $user->id,
            "full_name" => $user->full_name,
            "user_type" => $user->type,
            "status" => $user->status,
            "phone_number" => $user->phone_number,
            "custom_type"=>$this->getCustomType($user->id),
            "is_doctor" => false,
            "photo_profile" => $user->getFirstMediaFile("profile_photo") ? $user->getFirstMediaFile("profile_photo")->url : null,
        ];
        if($user->type == "u"){
            $data["user"]["switch_status"]='u';
        }else{
            $data["user"]["switch_status"]=$user->switch_status == 0 ? $user->type : "u";
        }
        $data["token"] = $token->accessToken;
        return $data;
    }

        public function show($contacts){
            $user_id=Auth::user()->id;
            $contacts_number=[];

            foreach ($contacts as $key=> $contact){
                $contacts_number[$key]=$contact["phone"];
            }

            $block_list=$this->block_list($user_id);
            $blockers=$this->blockers($user_id);
            $block_list=$blockers->concat($block_list);


            $users =User::whereIn("phone_number", $contacts_number)
                ->whereNotIn('id', $block_list)
                ->where([["id",'!=',$user_id],['type','u']])->get();
            $consultant=User::whereIn("phone_number", $contacts_number)
                ->whereNotIn('id', $block_list)
                ->where([["id",'!=',$user_id],['type','c'],['switch_status',1]])->get();
            $Data =$consultant->merge($users);


            foreach ($Data as $key => $contact){
                foreach ($contacts_number as $key2 => $number){
                    if($contact["phone_number"] == $number){
                        $Data[$key]["full_name"] = $contacts[$key2]["name"];
                    }
                }
            }

            return $Data;
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

    public function  update($request , $user_id){


        if($request->type){
           $type= $request->type == "user" ? "u":"c";
        }

        $user=User::find($user_id);
        $user->full_name = $request->full_name ?? $user->full_name;
        $user->phone_number = $request->phone_number ?? $user->phone_number;
        $user->firebase_uid = $request->firebase_uid ?? $user->firebase_uid;
        $user->age = $request->age ?? $user->age;
        $user->country = $request->country ??  $user->country;
        $user->type =  $type  ?? $user->type;
        $user->status =  $request->status  ?? $user->status;
        $user->date_of_birth = $request->date_of_birth ?? $user->date_of_birth;
        $user->description = $request->description ?? $user->description ;
        $user->diseases = $request->diseases ?? $user->diseases;
        if($user->save()){
            if($request->hasfile('profile_photo'))
            {
                if($user->getFirstMediaFile("profile_photo"))
                    $user->removeAllFiles();
                $user->saveMedia($request->file('profile_photo'), "profile_photo");
            }
        }
        return true;
        }


        public function blockusers($request){
            $user_id=Auth::user()->id; // who block users
        if(is_array($request->blocked_ids)){
            $users_blocked=$request->blocked_ids;
            foreach ($users_blocked as $block_user_id){
                if(!$this->is_blocked($user_id,$block_user_id)){
                    $block=new BlockedUsers();
                    $block->user_id=$user_id;
                    $block->type=1;
                    $block->block_id=$block_user_id;
                    $block->save();
                }

            }
        }else{
            if(!$this->is_blocked($user_id,$request->blocked_ids)) {
                $block = new BlockedUsers();
                $block->user_id = $user_id;
                $block->type = 1;
                $block->block_id = $request->blocked_ids;
                $block->save();
            }
        }
        return true;
        }

        public function is_blocked($user_id,$block_id){
           return BlockedUsers::where([["user_id",$user_id],["block_id",$block_id]])->exists();
        }
        public function get_blocked_users(){
            $user_id=Auth::user()->id;
            $blocked_users= BlockedUsers::where([["user_id",$user_id]])->get();
            $data=BlockedUsersResource::collection($blocked_users);
            return $data;
        }


        // who i block
        public function block_list($user_id){
            return BlockedUsers::where("user_id",$user_id)->get("block_id");
        }

        // who block me
        public function blockers($user_id){
            return BlockedUsers::where("block_id",$user_id)->get("user_id as block_id");
        }
}
