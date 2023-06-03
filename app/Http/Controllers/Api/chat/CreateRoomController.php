<?php

namespace App\Http\Controllers\Api\chat;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Helpers\Media\Models\Media;
use App\Http\Controllers\Controller;
use App\Models\MediaType;
use App\Models\RequestConsultant;
use App\Models\RequestConsultantChat;
use App\Models\RequestConsultation;
use App\Models\Room;
use App\Repositories\Api\chat\ChatRepository;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Mime\MimeTypes;

class CreateRoomController extends Controller
{

    protected  $repository;
    public function __construct(ChatRepository $repository)
    {
        $this->repository = $repository;
    }

    public function rules(){
        return [
//            "user_id"=> ["required"],
        ];
    }


    public function  createRoom(Request  $request){
        try {
            $valid = Validator::make($request->all(),  $this->rules());
            if($valid->fails()){
                return JsonResponse::validationErrors($valid->errors()->messages())->initAjaxRequest()->send();
            }
            $data = $this->repository->createRoom($request);
            if($data){
                if(array_key_exists('room_exist', $data))
                    return JsonResponse::data($data["room_exist"])->message(" room exist for this users")->send();
                return JsonResponse::data($data)->message("created room success")->send();
            }


        }catch (\Exception $e){
            return JsonResponse::error()->message($e->getMessage())->send();
        }
    }


    public function  showRoomChat(Request  $request)
    {
        try {
            $valid = Validator::make($request->all(),  ["room_id"=>["required"]]);
            if($valid->fails()){
                return JsonResponse::validationErrors($valid->errors()->messages())->initAjaxRequest()->send();
            }

            $data = $this->repository->getChat($request);
            return JsonResponse::data($data)->message("chat room")->send();


        }catch (\Exception $e){
            return JsonResponse::error()->message($e->getMessage())->send();
        }

    }

    public function message_status(Request  $request){

        try {
            $valid = Validator::make($request->all(),["message_id"=>["required"],"status"=>["required"]]);
            if($valid->fails()){
                return JsonResponse::validationErrors($valid->errors()->messages())->send();
            }

            $data = $this->repository->change_message_status($request);
            return JsonResponse::data($data)->message("chat room")->send();


        }catch (\Exception $e){
            return JsonResponse::error()->message($e->getMessage())->send();
        }
    }


    public function uploadMedia(Request $request)
    {
        $valid = Validator::make($request->all(), ["message_id"=>["required"],"image"=>["max:8192"]]);
        if($valid->fails()){
            return JsonResponse::validationErrors($valid->errors()->messages())->initAjaxRequest()->send();
        }
        $message_id = $request->message_id;
        $message= Message::find($message_id);

        if($request->hasFile("image")){
            $media=$request->file("image");
            if(is_array($media)){
                foreach ($media as $file){
                    $NewMedia=$message->savemedia($file,"Message");
                    $mediaT=new MediaType;
                    $mediaT->media_id=$NewMedia->id;
                    $mediaT->media_type=$this->getMediaType($file);
                    $mediaT->save();
                }
            }else{
                $NewMedia=$message->savemedia($media,"Message");
                $mediaT=new MediaType;
                $mediaT->media_id=$NewMedia->id;
                $mediaT->media_type=$this->getMediaType($media);
                $mediaT->save();
            }

        }
        return JsonResponse::success()->message("media saved success")->send();
    }

    public function checkUserConsultation(){
        $user_id=Auth::user()->id;
        if(RequestConsultation::where("user_id",$user_id)->whereNotIn('status', [5,4])->exists()){
            $RequestConsultation =RequestConsultation::where("user_id",$user_id)->whereNotIn('status', [5,4])->first();
            $rooms=Room::where(["id"=>$RequestConsultation->room_id])->first();
            return JsonResponse::data(["status"=>true,"room_id"=>$rooms->room_id])->message("exists request")->send();
        }else{
            return JsonResponse::data(["status"=>false,"room_id"=>""])->message("request not exists")->send();

        }
    }

    public function  get_room_request_counsaltant(Request $request){
        $valid = Validator::make($request->all(), ["user_id"=>"required"]);
        if($valid->fails()){
            return JsonResponse::validationErrors($valid->errors()->messages())->initAjaxRequest()->send();
        }
        $user_id=$request->user_id;
        if(RequestConsultant::where("user_id",$user_id)->exists()){
            $RequestConsultant=RequestConsultant::where("user_id",$user_id)->first();
            if(RequestConsultantChat::where("request_id",$RequestConsultant->id)->exists()){
                $RequestConsultantChat=RequestConsultantChat::where("request_id",$RequestConsultant->id)->first();
                $room_id= $RequestConsultantChat->room->room_id;
                $data=JsonResponse::data(["status"=>true,"room_id"=>$room_id])->message("exists request")->send();
            }else{
                $data=JsonResponse::data(["status"=>false,"room_id"=>""])->message("room not exists")->send();
            }
        }else{
            $data=JsonResponse::data(["status"=>false,"room_id"=>""])->message("room not exists")->send();
        }

        return $data;
    }

    public function getMediaType($file){

        $mime=$file->getClientMimeType();

        switch ($mime){
            case $this->getExt($mime, "image") :
                $type = 2;
            break;
            case $this->getExt($mime, "video") :
                $type = 3;
            break;
            case $this->getExt($mime, "audio") :
                $type = 4;
            break;
            case $this->getExt($mime, "application") :
                $type = 5;
            break;
            default :
                $type = 6;
        }
        return $type;
    }

   public function  getExt($text,$type){

        $sub_mimi=substr($text,0,strpos($text,'/'));
        if( $sub_mimi == $type){
            return  $text;
    }
        return "error";
    }


}
