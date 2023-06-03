<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BookingRating;
use App\Models\Doctor;
use App\Models\Specialization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\IntegrationTrait;

class DoctorController extends Controller
{
    use IntegrationTrait;

    public function getAllDoctorsBySpecialization(Request $request){
        $checkUser = Doctor::where("user_id", Auth::user()->id)->exists();
        if($checkUser)
        $data["doctors"] = Doctor::where([["specialization_id", $request->specialization_id], ["user_id", "!=", Auth::user()->id]])->get(["id", "full_name", "phone_number", "email", "specialization_id", "has_zoom", "object_id", "user_id","payment_methods", "description"]);
        else
            $data["doctors"] = Doctor::where("specialization_id", $request->specialization_id)->get(["id", "full_name", "phone_number", "email", "specialization_id", "has_zoom", "object_id","payment_methods", "description"]);
        for ($i=0; $i<count( $data["doctors"]); $i++){
            $data['doctors'][$i]["payment_methods"] = json_decode($data['doctors'][$i]["payment_methods"]);
            if($request->lang == "ar")
            $data['doctors'][$i]["specialization"] = Specialization::where("id", $data['doctors'][$i]["specialization_id"])->first()->name_ar;
            else
            $data['doctors'][$i]["specialization"] = Specialization::where("id", $data['doctors'][$i]["specialization_id"])->first()->name_en;

            $avg = BookingRating::where("rated_doctor",  $data['doctors'][$i]["id"])->pluck("rating_value")->avg();
            if(is_null($avg))
                $data['doctors'][$i]["rate"] = 5;
          //  $data['doctors'][$i]["rate"] = 0;
            else
                $data['doctors'][$i]["rate"] = 5;
              //  $data['doctors'][$i]["rate"] = $avg;
            $imageData = $data["doctors"][$i]->getFirstMediaFile("Doctors");
            $data["doctors"][$i]["image"] = $imageData != null  ? env("APP_PATH").$imageData["path"]."/".$imageData["filename"] : null;
            unset($data['doctors'][$i]["user_id"]);
        }
        return response()->json([
            "status" => true,
            "data" => $data["doctors"]
        ]);
    }

    public function getDoctorTimeSlots(Request $request){
        return $this->getObjectSlots($request);
    }
}
