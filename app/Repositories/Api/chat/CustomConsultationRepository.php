<?php

namespace App\Repositories\Api\chat;


use App\Http\Resources\Consultation\showConsultationResource;

use App\Http\Resources\Consultation\ShowCustomConsultationResource;
use App\Http\Resources\Consultation\ShowCustomTypeResource;
use App\Models\CustomConsultation;
use App\Models\UserCustomConsultation;
use http\Env\Request;
use Illuminate\Support\Facades\Auth;


class CustomConsultationRepository
{


    public function CustomConsultation(){

//        $type=CustomConsultation::getConsultationType();
//        return CustomConsultation::get([$type,'consultant_id']);

 $data =CustomConsultation::all();
 return ShowCustomTypeResource::collection($data);

    }

    public function CustomConsultationRepository($request){

        $user_id=Auth::user()->id;

        $status=$request->status;
        $CustomConsultation= UserCustomConsultation::where("consultation_status",$status)->orderBy('created_at', 'DESC')->get();
        $CustomConsultation=$this->check_is_same_type($CustomConsultation,$user_id);
        $data = ShowCustomConsultationResource::collection($CustomConsultation);
        return $data;

        }

        public function check_is_same_type($CustomConsultations,$user_id){
         foreach($CustomConsultations as $key => $CustomConsultation){
             if($CustomConsultation->custom_consultations != null){
                 if($CustomConsultation->custom_consultations->consultant_id != $user_id){
                     unset($CustomConsultations[$key]);
                 }
             }
         }
         return $CustomConsultations;
        }

}
