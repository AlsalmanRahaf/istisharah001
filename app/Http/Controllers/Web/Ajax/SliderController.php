<?php

namespace App\Http\Controllers\Web\Ajax;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Ads;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    //


    public function update(Request $request)
    {
        $slider_id = $request->id;
        $slider = Slider::find($slider_id);
        $slider->status = $request->status ?? $slider->status;
        if($slider->save()){
            return JsonResponse::success()->send();
        }
        return JsonResponse::error()->send();


    }
}
