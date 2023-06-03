<?php

namespace App\Http\Controllers\Api\ads;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\ads\AdsResource;
use App\Http\Resources\ads\LoadingAdsResource;
use App\Http\Resources\ads\ShowAdsResource;
use App\Http\Resources\ads\SupportAdsResource;
use App\Models\Ads_text;
use App\Models\LoadingAds;
use App\Models\UserChat;
use App\Repositories\Api\ads\AdsRepository;
use App\Traits\Firebase;
use App\Traits\Helper;
use Illuminate\Http\Request;
use App\Models\Ads;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class AdsController extends Controller
{
    use Firebase;

    protected  $repository;
    public function __construct(AdsRepository $repository)
    {
        $this->repository = $repository;
    }



    public function rules(){
        return [
            "type"        => ["required", "in:1,2,3"],
        ];
    }

    public function index(){
        $Ads=Ads::where("status",1)->get();
        $data = ShowAdsResource::collection($Ads);
        return JsonResponse::data($data)->send();
    }

    public function LoadingScreenAds()
    {
        $count=LoadingAds::count();
        $LoadingAds=[];
        if($count){
            $LoadingAds= LoadingAds::all()->random(1);
            $LoadingAds=LoadingAdsResource::collection($LoadingAds);
        }
        return JsonResponse::data($LoadingAds)->changeCode(200)->send();
    }



    public function create(Request $request)
    {

        $rules=$this->rules();
        $rules["Ads_file"] =$request->type != 1 ? ["required"]  : "";
        $valid = Validator::make($request->all(),  $rules);
        if($valid->fails()){
            return JsonResponse::validationErrors($valid->errors()->messages())->initAjaxRequest()->send();
        }

        $Ads= new Ads();
        $Ads->status= 1;
        $Ads->type= $request->type;
        $Ads->save();
        if($request->Data)
        {
            if(is_array($request->Data)) {

                foreach ($request->Data as $data){
                    $ads = new Ads_text();
                    $ads->Data = $data;
                    $ads->ads_id = $Ads->id;
                    $ads->save();
                }


            }else{
                $ads = new Ads_text();
                $ads->Data = $request->Data;
                $ads->ads_id = $Ads->id;
                $ads->save();
            }
        }




        if($request->hasfile('Ads_file'))
            foreach($request->file('Ads_file') as $file){
                $Ads->saveMedia($file,'Ads');
            }
        return JsonResponse::success()->message("created Ads success")->send();
    }

    public function create_support_ads(){

        $room_id=$this->repository->create_support_ads();
        if($room_id){
            return JsonResponse::data($room_id)->message("success")->changeCode(201)->send();
        }else{
            return JsonResponse::error()->message("admin not available")->changeCode(404)->send();
        }
    }

    public function get_support_ads_list(){
        $support_list=$this->repository->getSupportAdsList();
        $support_resource=SupportAdsResource::collection($support_list);
        return JsonResponse::data($support_resource)->message("success")->changeCode(200)->send();

    }
}
