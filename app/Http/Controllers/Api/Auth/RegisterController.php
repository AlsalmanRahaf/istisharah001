<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Helpers\ApiResponse\Json\Senders\SendData;
use App\Helpers\ApiResponse\Json\Senders\SendError;
use App\Helpers\ApiResponse\Json\Senders\SendValidationError;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class
RegisterController extends Controller
{

    protected  $repository;
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function rules(){
        return [
            "full_name"        => ["required", "max:255"],
            "firebase_uid"     => ["required", "unique:users"],
            "age"              => ["required","numeric","min:5","max:100"],
            "phone_number"     => ["required", "unique:users"],
            "type"             => ["required", "in:user,consultant"],
            "country"          => ["required"]
        ];
    }

    public function register(Request $request){
        try {


            $valid = Validator::make($request->all(),  $this->rules());

            if($valid->fails()){
                return JsonResponse::validationErrors($valid->errors()->messages())->initAjaxRequest()->send();
            }
            $data = $this->repository->register($request);
            return JsonResponse::data($data)->message("created user success")->send();
        }catch (\Exception $e){
            return JsonResponse::error()->message($e->getMessage())->send();
        }
    }
}
