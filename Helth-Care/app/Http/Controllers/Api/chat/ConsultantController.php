<?php

namespace App\Http\Controllers\Api\chat;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\consultation;
use App\Models\ReferredConsultation;
use App\Models\RequestConsultant;
use App\Models\User;
use App\Repositories\Api\chat\ChatRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class ConsultantController extends Controller
{
    //
    protected  $repository;
    public function __construct(ChatRepository $repository)
    {
        $this->repository = $repository;
    }


    public function rules(){

        $Consultants= User::where('type','c')->get("id");
        $ids=[];
        foreach ($Consultants as $Consultant){$ids[]=$Consultant->id;}

        return [
            "user_id"=>["required","exists:App\Models\User,id",Rule::notIn($ids)],
        ];
    }



        public function  createConsultant(Request  $request): \Illuminate\Http\JsonResponse
        {

            try {
                $valid = Validator::make($request->all(),  $this->rules());
                if($valid->fails()){
                    return JsonResponse::validationErrors($valid->errors()->messages())->initAjaxRequest()->send();
                }


                $data = $this->repository->CreateConsultant($request);

                if(is_array($data)){
                    return JsonResponse::data($data)->message("success")->send();
                }else{
                    return JsonResponse::error()->message("can't create Consultation")->send();
                }





            }catch (\Exception $e){
                return JsonResponse::error()->message($e->getMessage())->send();
            }
        }

    public function  UpdateConsultant(Request  $request): \Illuminate\Http\JsonResponse
    {

        $request->validate([
            "room_id"=>["required"],
            "status" => ["required",Rule::in([2,3,4])]
        ]);
        $consultation=consultation::where("room_id",$request->room_id)->findOrFail()->first();
        $consultation->consultations_status=$request->status;
        if($consultation->save()){
            return JsonResponse::success()->message("update success")->send();
        }else{
            return JsonResponse::error()->message("can't update")->send();

        }


    }


    public function  Consultation(Request  $request): \Illuminate\Http\JsonResponse
    {

        $request->validate([
            "room_id"=>["required"],
            "status" => ["required",Rule::in([2,3,4])]
        ]);
        $consultation=consultation::where("room_id",$request->room_id)->findOrFail()->first();
        $consultation->consultations_status=$request->status;
        if($consultation->save()){
            return JsonResponse::success()->message("update success")->send();
        }else{
            return JsonResponse::error()->message("can't update")->send();

        }
    }



    public function  RequestConsultant(Request  $request): \Illuminate\Http\JsonResponse
    {

        try {

            $valid = Validator::make($request->all(),[
                 "Practicing_the_profession"=>["required"]
                ,"personal_identification"=>["required"]
            ]);
            if($valid->fails()){
                return JsonResponse::validationErrors($valid->errors()->messages())->initAjaxRequest()->send();
            }

            if($this->repository->RequestConsultant($request)){
                return JsonResponse::success()->message("Request Consultant Completed ")->send();
            }else{
                return JsonResponse::error()->changeCode('404')->message("can't create Consultation or Exist or admin not available")->send();
            }





        }catch (\Exception $e){
            return JsonResponse::error()->message($e->getMessage())->send();
        }
    }

    public function  checkConsultantRequest(Request  $request): \Illuminate\Http\JsonResponse
    {
        $Consultant=Auth::user()->id;
        $message="";
        $RequestConsultant=RequestConsultant::where(["user_id"=>$Consultant])->get("status")->first();
        if($RequestConsultant){
            $status=$RequestConsultant->status;
        }else{
            return JsonResponse::error()->changeCode("404")->message("not found this Consultant")->send();
        }

           switch ($status){
               case 1:
                   $message=["status"=>$status,"data"=>"waiting"];
                   break;
               case 2:
                   $message=["status"=>$status,"data"=>"accepted"];
                   break;
               case 3:
                   $message=["status"=>$status,"data"=>"rejected"];
                   break;
               default:
                   return JsonResponse::error()->changeCode("404")->message("not found this status ")->send();

           }

          return JsonResponse::success()->message($message)->send();

        }




    public  function getConsultationByStatus(Request $request ): \Illuminate\Http\JsonResponse
    {
        try {

            $valid = Validator::make($request->all(),[
                "status"=>["required"],
                "location"=>["required"]
            ]);
            if($valid->fails()){
                return JsonResponse::validationErrors($valid->errors()->messages())->initAjaxRequest()->send();
            }
            $data = $this->repository->getConsultation($request);
            return JsonResponse::data($data)->message("get data success")->send();


        }catch (\Exception $e){
            return JsonResponse::error()->message($e->getMessage())->send();
        }

    }

    public function get_consultation_by_userid(Request $request){
        try {
            $valid = Validator::make($request->all(),[
                "user_id"=>["required"],
                "type"=>["required"]
            ]);
            if($valid->fails()){
                return JsonResponse::validationErrors($valid->errors()->messages())->initAjaxRequest()->send();
            }
            $data = $this->repository->get_consultation_by_userid($request);
            return JsonResponse::data($data)->message("get data success")->send();


        }catch (\Exception $e){
            return JsonResponse::error()->message($e->getMessage())->send();
        }
    }


    //  get Referred Consultation By Status
    public  function getReConsultationByStatus(Request $request ): \Illuminate\Http\JsonResponse
    {
        try {
            $valid = Validator::make($request->all(),[
                "status"=>["required"]
            ]);
            if($valid->fails()){
                return JsonResponse::validationErrors($valid->errors()->messages())->initAjaxRequest()->send();
            }
            $data = $this->repository->getReConsultation($request);
            return JsonResponse::data($data)->message("get data success")->send();


        }catch (\Exception $e){
            return JsonResponse::error()->message($e->getMessage())->send();
        }

    }

    public function check_block_consultant(Request $request){
        try {
            $valid = Validator::make($request->all(),[
                "consultation_id"=>["required"]
            ]);
            if($valid->fails()){
                return JsonResponse::validationErrors($valid->errors()->messages())->initAjaxRequest()->send();
            }
            $data = $this->repository->check_block_Consultation($request);
            return JsonResponse::data($data)->message("get data success")->send();


        }catch (\Exception $e){
            return JsonResponse::error()->message($e->getMessage())->send();
        }
    }


    public function getAllConsultant(): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->repository->getAllConsultant();
            return JsonResponse::data($data)->message("get data success")->send();

        }catch (\Exception $e){
            return JsonResponse::error()->message($e->getMessage())->send();
        }
    }

    public function change_consultant_status(Request  $request): \Illuminate\Http\JsonResponse
    {

        try {
            $valid = Validator::make($request->all(),[
                "switch_status"=>["required",Rule::in([0,1])],
                "user_id"=>["required"]
            ]);
            if($valid->fails()){
                return JsonResponse::validationErrors($valid->errors()->messages())->initAjaxRequest()->send();
            }
            $data = $this->repository->change_consultant_status($request);
            if($data)
               return JsonResponse::success()->message("update data success")->send();
            return JsonResponse::error()->message("Error update data")->send();


        }catch (\Exception $e){
            return JsonResponse::error()->message($e->getMessage())->send();
        }

    }

    public function getSpesialistConsultant(Request  $request)
    {
        dd(1);
        try {
            $data = $this->repository->getSpesialistConsultant($request);
            if($data)
                return JsonResponse::data($data)->message("get data success")->send();
            return JsonResponse::error()->message("Error update data")->send();


        }catch (\Exception $e){
            return JsonResponse::error()->message($e->getMessage())->send();
        }
    }

    public function getUnReadConsultation(Request  $request){
        try {
            $valid = Validator::make($request->all(),[
                "type"=>["required",Rule::in("c","sp","cs")]
            ]);
            if($valid->fails()){
                return JsonResponse::validationErrors($valid->errors()->messages())->initAjaxRequest()->send();
            }
            $data = $this->repository->getUnReadconsultation($request);
            return JsonResponse::data($data)->message("update data success")->send();
        }catch (\Exception $e){
            return JsonResponse::error()->message($e->getMessage())->send();
        }
    }

    public function RequestListConsultant(Request $request){
        try {
            $data = $this->repository->RequestListConsultant($request);
            return JsonResponse::data($data)->message("get data success")->send();
        }catch (\Exception $e){
            return JsonResponse::error()->message($e->getMessage())->send();
        }
    }

}
