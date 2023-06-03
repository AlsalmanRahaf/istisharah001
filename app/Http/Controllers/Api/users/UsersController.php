<?php

namespace App\Http\Controllers\Api\users;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;

use App\Http\Resources\users\promoCodeResource;
use App\Http\Resources\users\ShowUserResource;
use App\Http\Resources\users\UserResource;
use App\Models\consultation;
use App\Models\PromoCode;
use App\Models\User;
use App\Repositories\UserRepository;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{

    protected $repository;
    public function __construct(UserRepository $repository){
        $this->repository = $repository;
    }


    public function rules(){
        return [
            "full_name"        => [ "max:255"],
            "firebase_uid"     => [ "unique:users"],
            "age"              => ["numeric","max:100"],
            "phone_number"     => [ "unique:users"],
            "type"             => [ "in:user,consultant"]
        ];
    }

    public  function index(Request $request){

        $data = UserResource::collection(ShowUserResource::class,$this->repository->show($request->contact));
        return JsonResponse::data($data)->send();
    }

    public  function  show(){
        $user_id= Auth::user()->id;
        $user=User::where('id',$user_id)->get();
        $data = ShowUserResource::collection($user);
        return JsonResponse::data($data)->send();
    }

    public function checktoken(){
        return JsonResponse::success()->message("Token valid")->send();
    }

    public function hasConsultantChat(){
        $status=0;
        $user_id=Auth::user()->id;
        $consultation=consultation::where([["user_id",$user_id],["consultations_status",2]])->first();
        if($consultation->room != NULL && $consultation->room->user_chat != null){
            $user_chat=$consultation->room->user_chat;
            foreach ($user_chat as $chat){
                if($chat->status == 1 and $chat->user_id != $user_id){
                    $status=1;
                    break;
                }
            }
        }
        if($status){
            $data=JsonResponse::data(true)->message("this user have consultant message")->send();
        }else{
            $data=JsonResponse::data(false)->message("this user don't have consultant message")->send();
        }
        return $data;

    }

    public function show_promocode_data(Request $request){
       $user_id=Auth::user()->id;
       $promoCode=PromoCode::where("user_id",$user_id)->get();
       $data = UserResource::collection(promoCodeResource::class,$promoCode);
       return $data;

    }

    public function delete(){
        $user_id=Auth::user()->id;
        $user=User::find($user_id);
        if($user){
            $user->phone_number=null;
            $user->firebase_uid=null;
            if($user->save()){
                Auth::guard("api")->user()->token()->revoke();
            }
            return JsonResponse::success()->message("Delete user success")->send();
        }else{
            return JsonResponse::error()->message("This user was deleted")->changeCode(404)->send();
            }
    }





   //ajax dashboard and app
    public  function  update(Request  $request){
        if($request->ajax == 1){
            $user_id=$request->user_id;
        }else{
            $user_id= Auth::user()->id;
        }
        try {
            if($this->repository->update($request,$user_id))
                return JsonResponse::success()->message("update user success")->changeCode(200)->send();
            return JsonResponse::error()->message("update error")->send();
        }catch (\Exception $e){
            return JsonResponse::error()->message($e->getMessage())->send();
        }
    }


    public  function  ChangeUser(Request  $request){
        return $this->repository->ChangeUser();
    }

    public function changeNotificationLang(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $user->notification_lang = $request->lang;
        if($user->save())
        return response()->json([
            "status" => true,
            "message" => "Language updated successfully"
        ]);
        else
            return response()->json([
                "status" => false,
                "message" => "Something went error!"
            ]);
    }

}
