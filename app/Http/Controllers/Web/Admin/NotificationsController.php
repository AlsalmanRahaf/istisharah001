<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Http\Controllers\Controller;
use App\Models\Notifications;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\Firebase;

class NotificationsController extends Controller
{

    use Firebase;
    public function rules(){
        return [
            "Title"=>['required'],
            "Description"=>['required'],
            "type"=>["required"]

        ];
    }

    public function index()
    {
    }

    public function ShowSendForAll()
    {
        return view("admin.Notifications.send_for_all");
    }

    public function ShowSendForCustom()
    {

        $data["users"]=User::where("device_token","!=",null)->get();
        return view("admin.Notifications.send_for_custom",$data);
    }

    public function send(Request $request){

        $Title=$request->Title;
        $Description=$request->Description;
        $rule=$this->rules();
        $redirect="SendForCustom";

        if($request->type){
            $rule["id"]=["required","min:1"];
        }else{
            $rule["topic"]=["required","min:1"];
            $redirect="SendForAll";
        }

        $valid = Validator::make($request->all(),$rule);
        if($valid->fails()){
            return redirect()->route("admin.Notification.$redirect")->withInput($request->all())->withErrors($valid->errors()->messages());
        }

        if($request->type){
            $token =$this->getTokens(User::findMany($request->id));
        }else{
            $token =$request->topic;
            $type=$this->getType($token);
            $users=User::where("type",$type)->get("id");
            foreach ($users as $user){
                $Notifications=new Notifications();
                $Notifications->title=$Title;
                $Notifications->body=$Description;
                $Notifications->user_id=$user->id;
                $Notifications->type=1;
                $Notifications->status=1;
                $Notifications->save();
            }

        }

        $this->sendFirebaseNotificationCustom(["title"=>$Title,"body"=>$Description],$token);
        if(is_array($request->id)){
            foreach ($request->id as $user_id){
                $Notifications=new Notifications();
                $Notifications->title=$Title;
                $Notifications->body=$Description;
                $Notifications->user_id=$user_id;
                $Notifications->type=1;
                $Notifications->status=1;
                $Notifications->save();
            }
        }



        $message = (new SuccessMessage())->title("send notification successfully")
            ->body("notification  has been sent ");
        Dialog::flashing($message);

        return redirect()->route("admin.Notification.$redirect");

    }

    public function getTokens($users){
        $data=[];
        foreach ($users as $user){
            $data[]=$user->device_token;
        }
        return $data;
    }



    public function getType($type){

        switch ($type){
            case "users":
                $type="u";
                break;
            case "admin":
                $type="a";
                break;
            case "consultants":
                $type="c";
                break;
        }
        return $type;
    }

}
