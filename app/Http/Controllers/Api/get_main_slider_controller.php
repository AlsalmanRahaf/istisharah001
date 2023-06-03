<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\SliderMarket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class get_main_slider_controller extends Controller
{
    //
    public function getSlider(){

        $sliders = SliderMarket::all();
        $data = [];
        foreach ($sliders as $index => $slider){
            $data[$index]['id'] = $slider->id;
            $data[$index]['type'] = $slider->type;
            $data[$index]['navigate_id'] = $slider->navigate_id;
            $data[$index]['branch_id'] = $slider->branch_id ;
            $data[$index]['Status'] = $slider->Status;
            $data[$index]['created_at'] = $slider->created_at;
            $data[$index]['updated_at'] = $slider->updated_at;
            $data[$index]['Silder_image'] = $slider->getFirstMediaFile("slider_market_photo")->url;
        }


        return response($data);
    }


    public function navigateSlider(Request $request){

        if($request->type == 1){
            // $query=DB::select("SELECT * FROM `items_list` WHERE `item_status` = 1 and `category_id` = $id");

            // $query2=DB::select("SELECT `category_name_en`,`category_name_ar` FROM `category_list` where  `id` = $id");
            // return response()->json(["category"=> $query2,"data"=>$query]);

            $query2=DB::select("SELECT `name_en`,`name_ar` FROM `categories` where  `id` = $request->id");
            return response() -> json(["category" => $query2]);
        }

        else{
            $query=DB::select("SELECT * FROM `items` WHERE `status` = 1 and `id` = $request->id");

            return response($query);
        }


    }
}
