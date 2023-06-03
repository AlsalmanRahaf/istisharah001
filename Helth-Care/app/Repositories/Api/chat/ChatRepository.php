<?php

namespace App\Repositories\Api\chat;



use App\Http\Resources\chat\chatResource;
use App\Http\Resources\chat\ConsultationChatHistory;
use App\Http\Resources\chat\showChatResource;
use App\Http\Resources\Consultant\showConsultant;
use App\Http\Resources\Consultation\ReferredConsultationResource;
use App\Http\Resources\Consultation\RequestListConsultantChat;
use App\Http\Resources\Consultation\showConsultationResource;
use App\Http\Resources\Consultation\ShowReConsultationResource;
use App\Http\Resources\Consultation\ShowSpesialist;
use App\Models\BlockConsultantConsultation;
use App\Models\consultation;
use App\Models\CustomConsultation;
use App\Models\Message;
use App\Models\PromoCode;
use App\Models\ReferredConsultation;
use App\Models\RequestConsultant;
use App\Models\RequestConsultantChat;
use App\Models\RequestConsultation;
use App\Models\User;
use App\Models\UserChat;
use App\Models\UserCustomConsultation;
use App\Models\UserListConsultantSpesialist;
use App\Models\VipChat;
use App\Traits\Helper;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Room;
use App\Models\RoomMember;
use Illuminate\Support\Str;
use phpseclib3\Crypt\EC\Curves\brainpoolP160r1;


class ChatRepository
{

    use Helper;

    protected function checkRoomMember($request){

       $sendr_id=Auth::user()->id;
       $resiver_id = $request->user_id;
       return DB::select("SELECT rooms.room_id FROM `room_members` r1,`room_members` r2 , rooms WHERE rooms.id=r1.`room_id` and    r1.`room_id` = r2.`room_id` and r1.`user_id` =$sendr_id and r2.`user_id`=$resiver_id");

    }
    protected function checkConsultationStatus($user_id,$consultant_id,$room_id){

        // 1- booked up   2-available and have same consultant 3- available with New consultant

         $joinRoom=consultation::join("rooms","consultations.room_id","=","rooms.id");

         if(consultation::join("rooms","consultations.room_id","=","rooms.id")->where([["consultations.user_id",$user_id],["consultant_id",$consultant_id],["consultations.status",1],["consultations.room_id",$room_id]])->exists()){
             return 1;
         }elseif(consultation::join("rooms","consultations.room_id","=","rooms.id")->where([["consultations.user_id",$user_id],["consultant_id",$consultant_id],["consultations.status",2],["consultations.room_id",$room_id]])->exists()){
             return 2;
         }elseif(consultation::join("rooms","consultations.room_id","=","rooms.id")->where([["consultations.user_id",$user_id],["consultations.status",2],["consultations.room_id",$room_id]])->exists()){
             return 3;
         }


    }

    public function check_block_Consultation($request){
        $consultant_id=Auth::user();
        if($consultant_id->type == "a"){
            return false;
        }else{
            $consultation_id=  $request->consultation_id;
            return BlockConsultantConsultation::where("consultation_id",$consultation_id)->exists();
        }
    }

    protected function checkConsultationExist($consultant_id,$user_id)
    {
        $consultant = consultation::where([["user_id",$user_id]]);

        if ($consultant->exists())
            return true;
        return false;

    }

    protected  function addMember($user_id,$room_id){

    $registerMember=new RoomMember;
    $registerMember->user_id= $user_id;
    $registerMember->room_id= $room_id;
    $registerMember->save();

}
    public  function getChat($request){

        $data=[];
        $room_id=$request->room_id;
        $user_room=Room::where("room_id",$room_id)->first()->user_chat;
        $data = showChatResource::collection($user_room);
        return $data ;
    }

    public function change_message_status($request){
        $message_id=$request->message_id;
        $status=$request->status;
        $user_chat=UserChat::find($message_id);
        $user_chat->status=$status;
        if($user_chat->save()){
            $user_chat_id=$user_chat->id;
            $message=Message::where("user_chat_id",$user_chat_id)->first();
            $message->status=$status;
            if($message->save()){
                $url=env("NODEJSURL").'/message_seen';
                $this->sendRequest('post',[
                    'message_id' => $message_id,
                    'user_id'=>$user_chat->user_id
                ],$url);
            }
        }
        return true;
    }

    public function getConsultationbyLocation($request){

        $location =$request->location;
        $user =Auth::user();
        $data=[];

        if($user->type == "c"){
             if($location == 1){
                  $inside=RequestConsultation::join("consultation_locations","request_consultations.room_id","=","consultation_locations.room_id")
                    ->where([["consultation_locations.type",1],["consultation_locations.location",1]])
                    ->get("request_consultations.*");
                  $data=showConsultationResource::collection($inside);
             }elseif($location == 2){
                  $outside=RequestConsultation::join("consultation_locations","request_consultations.room_id","=","consultation_locations.room_id")
                    ->where([["consultation_locations.type",1],["consultation_locations.location",2]])
                    ->get("request_consultations.*");
                  $data=showConsultationResource::collection($outside);
             }
        }
        return $data;

    }

    public function createRoom($request)
    {
         $checkRoom =$this->checkRoomMember($request);
         //if find room
         if($checkRoom)
             return ["room_exist"=>$checkRoom[0]];


         //if no room
         $newRoom=new Room();
         $newRoom->room_id=Str::random(30);
         if($newRoom->save())
             $this->addMember(Auth::user()->id,$newRoom->id);
             if(is_array($request->user_id)) {
                 foreach ($request->user_id as $user_id)
                     $this->addMember($user_id, $newRoom->id);
             }else{
                 $this->addMember($request->user_id,$newRoom->id);
             }
             return ["room_id"=>$newRoom->room_id];
    }


    public function CreateConsultant($request)
    {

        $user_id=$request->user_id;
        $consultant_id=Auth::user()->id;

        $checkConsultant =$this->checkConsultationExist($consultant_id,$user_id);


        //if find room
        if($checkConsultant){


            $room_id=$request->room_id;
            $room=Room::where(["room_id"=>$room_id])->first();
            $room_id=$room->id;

            // 1- booked up   2-available and have same consultant 3- available with New consultant

            switch ($this->checkConsultationStatus($user_id,$consultant_id,$room_id)){
                case 1:
                    return ["status"=>"exist","room_id"=>$room->room_id];
                case 2:
                    consultation::where("consultant_id","!=",$consultant_id)->update(["status"=>1]);
                    $cons=consultation::where([["user_id",$user_id],["consultant_id",$consultant_id],["room_id",$room_id]])->first();
                    $cons->status=1;
                    if($cons->save()){
                        RequestConsultation::where(["user_id"=>$user_id],["room_id"=>$room_id])->update(["status"=>3]);
                    }

                    return ["status"=>"follow-Up","room_id"=>$room->room_id];
                case 3:
                    $newC = new consultation();
                    $newC->room_id = $room_id;
                    $newC->consultant_id = $consultant_id;
                    $newC->user_id = $user_id;
                    if ($newC->save()) {
                        RequestConsultation::where(["user_id"=>$user_id],["room_id"=>$room_id])->update(["status"=>3]);
                        return ["status"=>"follow-Up","room_id"=>$room->room_id];
                    }
                break;
                default :
                    return false;
            }

        }





        $newRoom=new Room();
        $newRoom->room_id=Str::random(30);
        $newRoom->chat_type=2;
        if($newRoom->save())

             $newC=new consultation();
             $newC->room_id=$newRoom->id;
             $newC->consultant_id=$consultant_id;
             $newC->user_id=$user_id;
             if($newC->save()){
                 RequestConsultation::where(["user_id"=>$user_id],["room_id"=>$newRoom->room_id])->update(["status"=>2]);
             }


              return ["status"=>"new","room_id"=>$newRoom->room_id];

    }



       public function RequestConsultant($request){

        $user_id=Auth::user()->id;



        if(!User::where([["type","c"],["id",$user_id]])->exists()){
            if(!RequestConsultant::where([["user_id",$user_id],["status",1]])->exists()){
                if(User::where([["type","a"],["status",1]])->exists()){
                    $RequestConsultant=new RequestConsultant();
                    $RequestConsultant->user_id=$user_id;
                    if($RequestConsultant->save()){

                        $newRoom=new Room();
                        $newRoom->room_id=Str::random(30);
                        if($newRoom->save()){
                            $admin = User::where([["type","a"],["status",1]])->first();
                            $this->addMember($user_id,$newRoom->id);
                            $this->addMember($admin->id,$newRoom->id);
                            $RequestConsultantChat =new RequestConsultantChat();
                            $RequestConsultantChat->request_id =$RequestConsultant->id;
                            $RequestConsultantChat->room_id =$newRoom->id;
                            $RequestConsultantChat->status =1;
                            $RequestConsultantChat->save();
                        }
                        $user=User::find($user_id);
                        if($request->hasfile("Practicing_the_profession") && $request->hasfile("personal_identification")){
                            $user->saveMedia($request->file('Practicing_the_profession'), "attach_photo");
                            $user->saveMedia($request->file('personal_identification'), "attach_photo");
                        }
                        return true;
                    }
                }

            }
        }
            return false;
    }

    public function getConsultation($request){

       $status=$request->status;
       $location=$request->location;
        $Consultant= Auth::user();
        if($Consultant->type != "u"){
            switch ($location){
               case 0:
                   $RequestConsultation= RequestConsultation::where("status",$status)->orderBy('created_at', 'DESC')->get();
                   break;
               case 1:
                   $RequestConsultation= RequestConsultation::join("consultation_locations","request_consultations.room_id","=","consultation_locations.room_id")
                       ->orderBy('request_consultations.created_at', 'DESC')
                       ->where([["consultation_locations.type",1],["consultation_locations.location",1],["request_consultations.status",$status]])
                       ->get("request_consultations.*");
                       break;
               case 2:
                   $RequestConsultation= RequestConsultation::join("consultation_locations","request_consultations.room_id","=","consultation_locations.room_id")
                       ->orderBy('request_consultations.created_at', 'DESC')
                       ->where([["consultation_locations.type",1],["consultation_locations.location",2],["request_consultations.status",$status]])
                       ->get("request_consultations.*");
                   break;
           }
           $data = showConsultationResource::collection($RequestConsultation);
           return $data;
       }

    }

    public function get_consultation_by_userid($request){

        $user_id= $request->user_id;
        $Consultations=[];
        $chat =[];

        $type= $request->type;
        switch($type){
            case "c":
               $Consultations= consultation::where("user_id",$user_id)->get();
            break;
            case "sp":
               $Consultations= ReferredConsultation::where("user_id",$user_id)->get();
            break;
            case "cs":
                $Consultations=UserCustomConsultation::where("user_id",$user_id)->get();
            break;
            case "a":
                $Consultations = consultation::where("user_id",$user_id)->get();
                $ReferredConsultation= ReferredConsultation::where("user_id",$user_id)->get();
                $UserCustomConsultation=UserCustomConsultation::where("user_id",$user_id)->get();
                $Consultations = $Consultations->merge($ReferredConsultation);
                $Consultations = $Consultations->merge($UserCustomConsultation);

        }
        if(!empty($Consultations)){
            foreach ($Consultations as $Consultation){
                $user_room=Room::where("id",$Consultation->room_id)->first()->user_chat;
                $chat[] =showChatResource::collection($user_room);
            }
        }

        return $chat;

    }

    public function getAllConsultant(){

        $Consultants=User::whereNotIn("type",["a"])->get();
        foreach ($Consultants as $key => $consultant){
           if($consultant->custom_consultations == null && $consultant->type == "u"){
               unset($Consultants[$key]);
           }
        }
        $data = showConsultant::collection($Consultants);
        return $data;
    }

    public function change_consultant_status($request){
        $user_id=$request->user_id;
        $newStatus=$request->switch_status;
        $User=User::find($user_id);
        $User->switch_status=$newStatus;
        return $User->save();
    }

    public function getReConsultation($request){

        $data=[];
        $status=$request->status;
        $Consultant= Auth::user();

        if($Consultant->type != "c" || $Consultant->type == "a"){
            $ReferredConsultation= ReferredConsultation::where([["consultant_id",$Consultant->id],["status",$status]])->get();
            $data = showReConsultationResource::collection($ReferredConsultation);
        }
        return $data;
    }

    public function getSpesialistConsultant(){
        $user_id=Auth::user()->id;
        $ReferredConsultation=UserListConsultantSpesialist::join("referred_consultations","user_list_consultant_spesialists.room_id","=","referred_consultations.room_id")
            ->orderBy('referred_consultations.created_at', 'DESC')
            ->where("user_list_consultant_spesialists.user_id",$user_id)
            ->where("referred_consultations.status",2)
            ->orWhere("referred_consultations.status",1)
            ->get();

        $data = ShowSpesialist::collection($ReferredConsultation);
        return $data;
    }
    public function getUnReadConsultation($request){

        $staus=false;
        switch ($request->type){
            case "c":
               $consultations= consultation::where("consultations_status",2)->get();
               break;
            case "sp":
                $consultations= ReferredConsultation::where("status",2)->get();
                break;
            case "cs":
                $consultations= UserCustomConsultation::where("consultation_status",2)->get();
                break;
        }
        $staus=$this->getChatStatus($consultations);
        if($staus){
            $url=env("NODEJSURL").'/getUnReadConsultation';
            $this->sendRequest('post',[
                'type' => $request->type,
            ],$url);
        }
        return $staus;
    }

    public function getChatStatus($consultations){
        $status=false;

        foreach ($consultations as $consultation){
            if($consultation->room != null && $consultation->room->user_chat != null){

                $user_chats =$consultation->room->user_chat;
                foreach ($user_chats as $chat){
                    if($chat->status == 1 && $chat->users->type == "u"){
                        $status= true;
                    }
                }
            }
        }
        return $status;
    }

    public function RequestListConsultant($request){
        $RequestListConsultant=RequestConsultantChat::all();
        $data = RequestListConsultantChat::collection($RequestListConsultant);
        return $data;
    }






    public function getAllConsultationForUser()
    {
        $user_id=Auth::user()->id;
        $AllConsultation=consultation::where("user_id",$user_id)
            ->whereIn("consultations_status",[4,5])
            ->orderBy('id', 'DESC')
            ->get(["room_id","created_at"])->filter(function($item) {return $item->created_at->toDateString();});


        $AllReferredConsultation=ReferredConsultation::where("user_id",$user_id)
            ->whereIn("status",[3,4])
            ->orderBy('id', 'DESC')
            ->get(["room_id","created_at","type"])->filter(function($item) {return $item->created_at->toDateString();});

        $AllCustomConsultation=UserCustomConsultation::where("user_id",$user_id)
            ->whereIn("consultation_status",[3,4])
            ->orderBy('id', 'DESC')
            ->get(["room_id","created_at"])->filter(function($item) {return $item->created_at->toDateString();});

        $data["normal"]=ReferredConsultationResource::collection($AllConsultation);
        $data["spatialist"]=ReferredConsultationResource::collection($AllReferredConsultation);
        $data["custom"]=ReferredConsultationResource::collection($AllCustomConsultation);

        return $data;
    }

    public function deleteChat($request){
        $ids=$request->chat_id;
        if(is_array($ids)){
            foreach ($ids as $id){
                $user_chat = UserChat::find($id);
                if($user_chat != null) {
                    $user_chat->status = 0;
                    $user_chat->save();
                }
            }
        }else{
            $user_chat = UserChat::find($ids);
            $user_chat->status=0;
            $user_chat->save();
        }
        return true;

    }

    public function Is_VIP($id){
       return VipChat::where("chat_id",$id)->exists();
    }




//    public function getConsultationChatHistory($room_id){
//            $chatRoom=Room::where("id",$room_id)->first()->user_chat;
//            $data=chatResource::collection(ConsultationChatHistory::class,$chatRoom);
//        return $data;
//    }


}
