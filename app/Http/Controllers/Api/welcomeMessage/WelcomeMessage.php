<?php

namespace App\Http\Controllers\Api\welcomeMessage;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;

class WelcomeMessage extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function index()
    {
        $data =\App\Models\WelcomeMessage::all();
        return JsonResponse::data($data)->send();
    }


}
