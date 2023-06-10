<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use App\Models\User;
use App\Repositories\ProfileRepository;
use App\Repositories\UserRepository;
use App\Rules\HashMatching;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{

    protected ProfileRepository $repository;

    public function __construct(ProfileRepository $repository)
    {
        $this->repository = $repository;
    }


    public function show(Request $request)
    {
        dd("jkjkjkjk");
        $user = Auth::guard("api")->user();
        return JsonResponse::data(ProfileResource::make($user))->send();
    }

    public function update(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::guard("api")->user();
        $vaild = Validator::make($request->all(), $this->repository->rules($request, $user));
        if ($vaild->fails()) {
            return JsonResponse::validationErrors($vaild->errors()->messages())->changeCode(200)->changeStatusNumber('S400')->initAjaxRequest()->send();
        }

        $this->repository->saveProfile($request, $user);
        return JsonResponse::success()->send();
    }


}
