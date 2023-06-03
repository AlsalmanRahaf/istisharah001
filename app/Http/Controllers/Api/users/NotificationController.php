<?php

namespace App\Http\Controllers\Api\users;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\users\NotificationResource;
use App\Models\Notifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request  $request)
    {
        if (Auth::user() !== null) {
            $user_id = Auth::user()->id;
            $count=Notifications::where([["user_id",$user_id],["status",1]])->count();
            $notification = Notifications::where([["user_id",$user_id],["type",1]])->orderBy('created_at', 'DESC')->get(["id","title","body","created_at","status","user_id"]);;
            $resource=NotificationResource::collection($notification);
            return JsonResponse::data(["count"=>$count,"notification"=>$resource])->changeCode(200)->send();
        } else {
            return JsonResponse::error()->send();
        }
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
     * @return \App\Helpers\ApiResponse\Json\Senders\SendSuccess
     */
    public function update(Request $request)
    {
        $valid=  Validator::make($request->all(),["id"=>"required","status"=>["required","in:1,2"]]);
        if($valid->fails()){
            return JsonResponse::validationErrors($valid->errors()->messages())->send();
        }
     $id=$request->id;
     $status=$request->status;

     $Notification=Notifications::find($id);
     $Notification->status=$status;
     if($Notification->save()){
         $response=JsonResponse::success()->changeCode(200)->message("update notification success")->send();
     }else{
         $response=JsonResponse::error()->message("update notification fail")->send();

     }
     return $response;
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
