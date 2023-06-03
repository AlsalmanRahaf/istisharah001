<?php

namespace App\Http\Controllers\Web\Ajax;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Ads;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //


    public function update(Request $request)
    {
        $Admin_id = $request->id;
        $Admin = Admin::find($Admin_id);
        $Admin->status = $request->status ?? $Admin->status;
        if($Admin->save()){
            return JsonResponse::success()->send();
        }
        return JsonResponse::error()->send();


    }
}
