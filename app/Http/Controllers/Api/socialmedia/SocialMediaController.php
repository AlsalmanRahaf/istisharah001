<?php

namespace App\Http\Controllers\Api\socialmedia;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\socialMedia\ShowSocialMediaResource;
use App\Models\SocialMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SocialMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \string[][]
     */



    public function rules(){
        return [
            "type"        => ["required"],
            "url"        => ["required"],
        ];
    }



    public function index()
    {
         $SocialMedia=SocialMedia::all();
         $data = ShowSocialMediaResource::collection($SocialMedia);
         return JsonResponse::data($data)->send();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request  $request)
    {
        //
        $rules=$this->rules();

        $valid = Validator::make($request->all(),  $rules);

        if($valid->fails()){
            return JsonResponse::validationErrors($valid->errors()->messages())->initAjaxRequest()->send();
        }


        $social= new SocialMedia();
        $social->type= $request->type;
        $social->url= $request->url;
        $social->save();
        if($request->hasfile('social_photo'))
            $social->saveMedia($request->file('social_photo'),'SocialMedia');


        return JsonResponse::success()->message("created social success")->send();

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
