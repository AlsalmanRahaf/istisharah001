<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Specialization;
use Illuminate\Http\Request;

class SpecializationController extends Controller
{
    public function getAllSpecialization(Request $request){
        $data["specializations"] = Specialization::all();
        for ($i=0; $i<count( $data["specializations"]); $i++){
            $imageData = $data["specializations"][$i]->getFirstMediaFile("Specializations");
            $data["specializations"][$i]["image_url"] = $imageData != null  ? env("APP_PATH").$imageData["path"]."/".$imageData["filename"] : null;
            if($request->lang == "ar"){
                $data["specializations"][$i]["name"] = $data["specializations"][$i]["name_ar"];
                $data["specializations"][$i]["description"] = $data["specializations"][$i]["description_ar"];
            }
            else {
                $data["specializations"][$i]["name"] = $data["specializations"][$i]["name_en"];
                $data["specializations"][$i]["description"] = $data["specializations"][$i]["description_en"];
            }
            unset($data["specializations"][$i]["name_ar"]);
            unset($data["specializations"][$i]["name_en"]);
            unset($data["specializations"][$i]["description_ar"]);
            unset($data["specializations"][$i]["description_en"]);
        }
        return response()->json([
            "status" => true,
            "data" => $data["specializations"]
        ]);
    }
}
