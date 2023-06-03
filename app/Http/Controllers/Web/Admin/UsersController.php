<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\DangerMessage;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Http\Controllers\Controller;
use App\Http\Resources\web\CRUDResource;
use App\Http\Resources\web\ShowRequestConsultant;
use App\Models\RequestConsultant;
use App\Models\RequestConsultation;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\Helper;
class UsersController extends Controller
{
    use Helper;
    //
    protected function rules(){
        return [
            "status" => ["required","in:1,2,3"],
        ];
    }

    public function index(Request $request){
        if(isset($request->type)){
            $type=$request->type ;
            $data["users"] = User::where("type",$type)->get();
        }else{
            $data["users"] = User::all();
        }

        $data["type"]=["c"=>"Consultant","a"=>"admin","u"=>"user","cph"=>"Consultant Pharmacist","cn"=>"Consultant nutrition","cd"=>"Consultant diabetes"];
        return view("admin.users.index",$data);
    }



    public function update(Request $request){
        $user_id=$request->user_id;
        $user=User::find($user_id);
        $user->status=$request->status ?? $user->status;
        $user->type=$request->type ?? $user->type;
        $user->switch_status=0;
        if($user->save()){
            if(isset($request->type)){
                $url=env("NODEJSURL").'/change_user_type';
                $this->sendRequest('post',[
                    'user_id' => $user_id,
                    'type' => $user->type,
                ],$url);
            }
            if(isset($request->status) && $request->status== 2 ){
                $url=env("NODEJSURL").'/block_user';
                $this->sendRequest('post',[
                    'user_id' => $user_id,
                ],$url);
            }

        }

        return redirect()->route("admin.users.index");

    }
    public function ShowRequestConsultant(){
        $data["RequestsConsultants"]=RequestConsultant::all();
        $data["rooms"]=$this->getRequestConsultantChat();
        return view("admin.users.requestConsultant",$data);
    }

    public  function UpdateRequestConsultant(Request  $request){

        $valid = Validator::make($request->all(),["status" => ["required","in:1,2,3,4"]]);
        if($valid->fails()){
            $message = (new DangerMessage())->title("Update Fail")
                ->body("Update Request Fail");
            Dialog::flashing($message);
            return redirect()->route("admin.users.RequestConsultant")->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $id= $request->id;
        $status=$request->status;
        if(RequestConsultant::find($id)->exists()){
            $requestConsultant=RequestConsultant::find($id);
            $requestConsultant->status=$status;
            $requestConsultant->save();
            if($status == 2){
                $user_id =$requestConsultant->user_id;
                $user=User::find($user_id);
                $user->type="c";
                if($user->save()){
                    $url=env("NODEJSURL").'/change_user_type';
                    $this->sendRequest('post',[
                        'user_id' => $user_id,
                        'type' => $user->type,
                    ],$url);
                }
            }
            $message = (new SuccessMessage())->title("Updated Successfully")
                ->body("The status Has Been Updated Successfully");
            Dialog::flashing($message);
            return redirect()->route("admin.users.RequestConsultant");
        }

    }

    public function DeleteRequestConsultant(Request  $request){
        $id= $request->id;
        if(RequestConsultant::find($id)->exists()){
            if(RequestConsultant::find($id)->delete()){
                $message = (new SuccessMessage())->title("Delete Successfully")
                    ->body("The Request Has Been Delete Successfully");
                Dialog::flashing($message);
                return redirect()->route("admin.users.RequestConsultant");
            }else{
                $message = (new DangerMessage())->title("Delete Fail")
                    ->body("Delete Request Fail");
                Dialog::flashing($message);
                return redirect()->route("admin.users.RequestConsultant")->withErrors($request->errors()->messages());}
        }
    }

    public function getRequestConsultantChat(){
        $all=Room::where("chat_type",1)->get();
        $rooms=[];

        foreach ($all as $room){

            if($room->getRequestConsultantChat != null){
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
