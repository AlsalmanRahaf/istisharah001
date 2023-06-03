<?php

namespace App\Http\Controllers\Api\chat;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\chat\ConsultationLocationResource;
use App\Http\Resources\Consultation\showConsultationResource;
use App\Models\consultation;
use App\Models\ConsultationLocation;
use App\Repositories\Api\chat\ChatRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ConsultationLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    protected  $repository;
    public function __construct(ChatRepository $repository)
    {
        $this->repository = $repository;
    }


    public function index(Request $request)
    {
        try {
            $valid = Validator::make($request->all(),[
                "location"=>["required"]
            ]);
            if($valid->fails()){
                return JsonResponse::validationErrors($valid->errors()->messages())->initAjaxRequest()->send();
            }
            $data = $this->repository->getConsultationbyLocation($request);
            return JsonResponse::data($data)->message("get data success")->send();


        }catch (\Exception $e){
            return JsonResponse::error()->message($e->getMessage())->send();
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
