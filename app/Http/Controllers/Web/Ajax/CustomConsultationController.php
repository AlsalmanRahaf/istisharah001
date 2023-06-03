<?php

namespace App\Http\Controllers\Web\Ajax;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\DangerMessage;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Http\Controllers\Controller;
use App\Http\Resources\web\CRUDResource;
use App\Http\Resources\web\ShowRequestConsultant;
use App\Models\CustomConsultation;
use App\Models\RequestConsultant;
use App\Models\RequestConsultation;
use App\Models\User;
use App\Traits\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomConsultationController extends Controller
{
    //
    use Helper;
    public function update(Request $request)
    {
        $consultant_id=$request->id;
        $consultant= CustomConsultation::find($consultant_id);
        $consultant->status=$request->status ?? $consultant->status;
        if($consultant->save()){
            if($consultant->status == 0){
                $User=User::find($consultant->consultant_id);
                $url=env("NODEJSURL").'/change_user_type';
                $this->sendRequest('post',[
                    'user_id' => $User->id,
                    'type' => $User->type,
                ],$url);
            }else{
                $url=env("NODEJSURL").'/change_user_to_other_consultant';
                $this->sendRequest('post',[
                    'user_id' => $consultant->consultant_id,
                    'type' =>$consultant->consultation_name_en
                ],$url);
            }
            return JsonResponse::success()->send();
        }
        return JsonResponse::error()->send();


    }
}
