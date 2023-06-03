<?php

namespace App\Http\Controllers\Api\promoCode;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use App\Repositories\Api\promoCode\PromoCodeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PromoCodeController extends Controller
{
    //
    protected $repository;

    public function __construct(PromoCodeRepository $repository)
    {
        $this->repository = $repository;
    }


    public function createPromoCode()
    {

        try {
            $data = $this->repository->createPromoCode();
            if ($data)
                return JsonResponse::data($data)->changeCode(201)->message("created promo code success")->send();
            return JsonResponse::error()->changeCode(404)->message("Error create promo code or exist ")->send();


        } catch (\Exception $e) {
            return JsonResponse::error()->message($e->getMessage())->send();
        }
    }

    public function UsesPromoCode(Request $request)
    {

        try {
            $valid = Validator::make($request->all(), ["promo_code"=>"required"]);
            if ($valid->fails()) {
                return JsonResponse::validationErrors($valid->errors()->messages())->initAjaxRequest()->send();
            }

            $data = $this->repository->UsesPromoCode($request);
            if ($data == 1) {
                    return JsonResponse::success()->message("PromoCode used success")->send();
            }
            return JsonResponse::error()->changeCode(404)->message($data)->send();



        } catch (\Exception $e) {
            return JsonResponse::error()->message($e->getMessage())->send();
        }

    }


}
