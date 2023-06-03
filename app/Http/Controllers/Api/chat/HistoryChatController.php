<?php

namespace App\Http\Controllers\Api\chat;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use App\Repositories\Api\chat\ChatRepository;
use App\Repositories\Api\chat\ConsultantAdminRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class HistoryChatController extends Controller
{
    //

    protected  $repository;
    public function __construct(ChatRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getConsultationChatHistory(Request $request)
    {
        $data=$this->repository->getAllConsultationForUser();
        return JsonResponse::data($data)->message("success")->send();
    }
    public function deleteChat(Request $request){


        $valid = Validator::make($request->all(),["chat_id"=>"required"]);
        if($valid->fails()){
            return JsonResponse::validationErrors($valid->errors()->messages())->initAjaxRequest()->send();
        }
        $this->repository->deleteChat($request);
            return JsonResponse::success()->changeCode(200)->message("delete data success")->send();
    }
}
