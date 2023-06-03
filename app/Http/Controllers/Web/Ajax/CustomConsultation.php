<?php

namespace App\Http\Controllers\Web\Ajax;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\DangerMessage;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Http\Controllers\Controller;
use App\Http\Resources\web\CRUDResource;
use App\Http\Resources\web\ShowRequestConsultant;
use App\Models\RequestConsultant;
use App\Models\RequestConsultation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomConsultation extends Controller
{
    //
    public function update(Request $request)
    {
        $consultant_id=$request->consultant_id;
        $consultant= \App\Models\CustomConsultation::find($consultant_id);
        $consultant->status=$request->status ?? $consultant->status;
        if($consultant->save()){
            return JsonResponse::success()->send();
        }
        return JsonResponse::error()->send();


    }
}
