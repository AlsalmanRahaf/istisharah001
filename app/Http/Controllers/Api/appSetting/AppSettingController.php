<?php

namespace App\Http\Controllers\Api\appSetting;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\chat\ButtonResource;
use App\Models\AppSetting;
use App\Models\ButtonSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \string[][]
     */


    public function rules(){
        return [
            "Terms_And_Conditions" => ["required"]
        ];
    }



    public function index()
    {
        //
        $AppSetting=AppSetting::all();
        return JsonResponse::data($AppSetting)->send();
    }


    public function check()
    {
        $AppSetting=AppSetting::get("contest");
        return JsonResponse::data($AppSetting)->send();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request  $request)
    {


        $rules=$this->rules();
        $valid = Validator::make($request->all(),  $rules);

        if($valid->fails()){
            return JsonResponse::validationErrors($valid->errors()->messages())->initAjaxRequest()->send();
        }

        $Ads= new AppSetting();
        $Ads->Terms_And_Conditions= $request->Terms_And_Conditions;
        $Ads->save();

        return JsonResponse::success()->message("created AppSetting success")->send();


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


    public function getMainButtons(Request $request)
    {
        $type=$request->type;
        $buttons=ButtonSetting::where("type",$type)->get();
        return $buttons;

    }


}
