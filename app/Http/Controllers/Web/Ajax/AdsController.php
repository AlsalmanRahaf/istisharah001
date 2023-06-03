<?php

namespace App\Http\Controllers\Web\Ajax;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Ads;
use App\Models\LoadingAds;
use App\Models\User;
use Illuminate\Http\Request;

class AdsController extends Controller
{
    //


    public function update(Request $request)
    {
        $ads_id = $request->id;
        $ads = Ads::find($ads_id);
        $ads->status = $request->status ?? $ads->status;
        if($ads->save()){
            return JsonResponse::success()->send();
        }
        return JsonResponse::error()->send();
    }

    public function updateloadingAds(Request $request)
    {
        $ads_id = $request->id;
        $ads = LoadingAds::find($ads_id);
        $ads->status = $request->status ?? $ads->status;
        if($ads->save()){
            return JsonResponse::success()->send();
        }
        return JsonResponse::error()->send();
    }

}
