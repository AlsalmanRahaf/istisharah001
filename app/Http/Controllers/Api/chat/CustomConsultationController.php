<?php

namespace App\Http\Controllers\Api\chat;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\chat\chatResource;
use App\Models\CustomConsultation;
use App\Models\Room;
use App\Models\UserCustomConsultation;
use App\Repositories\Api\chat\CustomConsultationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CustomConsultationController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected  $repository;
    public function __construct(CustomConsultationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $getAllCustom = $this->repository->CustomConsultation();
        return JsonResponse::data($getAllCustom)->send();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkUserConsultation(){

        $user_id=Auth::user()->id;

        if(UserCustomConsultation::where("user_id",$user_id)->whereNotIn('status', [5,4])->exists()){
            $RequestConsultation =UserCustomConsultation::where("user_id",$user_id)->whereNotIn('status', [5,4])->first();
            $rooms=Room::where(["id"=>$RequestConsultation->room_id])->first();
            $data=["status"=>true,"room_id"=>$rooms->room_id];
        }else{
            $data=["status"=>false,"room_id"=>""];
        }
        return JsonResponse::data($data)->message("success")->send();
    }


    public function get_custom_consultation_by_Status(Request  $request){
        try {
            $valid = Validator::make($request->all(),[
                "status"=>["required"]
            ]);
            if($valid->fails()){
                return JsonResponse::validationErrors($valid->errors()->messages())->initAjaxRequest()->send();
            }

            $data = $this->repository->CustomConsultationRepository($request);
            return JsonResponse::data($data)->message("get data success")->send();


        }catch (\Exception $e){
            return JsonResponse::error()->message($e->getMessage())->send();
        }
    }


    public function update(){
        return 1;
    }

}
