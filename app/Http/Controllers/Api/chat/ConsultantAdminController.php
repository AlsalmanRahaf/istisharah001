<?php

namespace App\Http\Controllers\Api\chat;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use App\Repositories\Api\chat\ConsultantAdminRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class ConsultantAdminController extends Controller
{
    //
    protected  $repository;
    public function __construct(ConsultantAdminRepository $repository)
    {
        $this->repository = $repository;
    }



    public function create_consultant_admin_room(Request $request){

        try {

            $valid=  Validator::make($request->all(),$this->repository->Rules());
            if($valid->fails()){
                return JsonResponse::validationErrors($valid->errors()->messages())->initAjaxRequest()->send();
            }


            $data = $this->repository->create_room($request);

            if(!isset($data["message"])){
                return JsonResponse::data($data)->message("success")->send();
            }else{
                return JsonResponse::error()->changeCode(404)->message($data["message"])->send();
            }


        }catch (\Exception $e){
            return JsonResponse::error()->message($e->getMessage())->send();
        }
    }

    public function block_consultant_consultation(Request $request){
        try {

            $valid=  Validator::make($request->all(),["consultant_id"=>["required"],"type"=>["required","in:1,2,3"],"status"=>["required","in:1,2"]]);
            if($valid->fails()){
                return JsonResponse::validationErrors($valid->errors()->messages())->initAjaxRequest()->send();
            }


            $data = $this->repository->block_consultant_consultation($request);

            if($data){
                return JsonResponse::data($data)->message("success")->send();
            }else{
                return JsonResponse::error()->changeCode(404)->message("you are not admin")->send();
            }


        }catch (\Exception $e){
            return JsonResponse::error()->message($e->getMessage())->send();
        }
    }
}
