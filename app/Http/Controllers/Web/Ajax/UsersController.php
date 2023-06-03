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

class UsersController extends Controller
{
    //
    public function update(Request $request)
    {
        $user_id = $request->id;
        $user = User::find($user_id);
        $user->status = $request->status ?? $user->status;
        if($user->save()){

            return JsonResponse::success()->send();
        }
        return JsonResponse::error()->send();


    }
}
