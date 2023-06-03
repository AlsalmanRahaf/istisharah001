<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\ConsultantRequest;
use App\Models\ObjectWeekDays;
use App\Models\TimeSlotType;
use Carbon\Carbon;


use Illuminate\Http\Request;
class ConsultantRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        dd('hiii');
 }
//    public function toArray(Request $request): array
//    {
//        dd("jhhh");
//        return [
//            'id' => $this->id,
//            'name' => $this->name,
//            'email' => $this->email,
//            'phone' => $this->phone,
//            'online_price' =>$this->online_price,
//            'created_at' => $this->created_at,
//            'updated_at' => $this->updated_at,
//            'offline_price'=>$this->offline_price,
//            'request_type' =>$this->request_type
//
//        ];
//    }
//}
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
//     */
    public function add(Request $req)
    {
        dd("hiii");
            $consultant= new ConsultantRequest;
            $time_slot_type= new TimeSlotType();
            $object_week_day=new ObjectWeekDays();

            $consultant->id = $req->id;
            $consultant->name = $req->name;
            $consultant->email = $req->email;
            $consultant->phone = $req->phone;
            $consultant->online_price =$req->online_price;
            $consultant->created_at = $req->created_at;
            $consultant->updated_at = $req->updated_at;
            $consultant->offline_price=$req->offline_price;
            $consultant->request_type =$req->request_type;
            $consultant->saveMedia($req->file("img"));

            $time_slot_type->slot_duration=$req->slot_duration;
            $time_slot_type->consultant_id=$req->consultant_id;
            $time_slot_type->wating_time=$req->wating_time;
        dd("$time_slot_type");

            $object_week_day->time_slot_type_id=$req->time_slot_type_id;
            $object_week_day->week_day_number=$req->week_day_number;
            $object_week_day->week_day_en_name=$req->week_day_en_name;
           $object_week_day->week_day_ar_name=$req->week_day_ar_name;

            $object_week_day->is_off=$req->is_off;
            $object_week_day->time_from=$req->time_from;
            $object_week_day->time_to=$req->time_to;

            $result=$consultant.$time_slot_type.$object_week_day->save();
            dd("$result");

           if($result)
           {
               return ["Result"=>"created"];
           }else{
               return ["Result"=>"NOTcreated"];

           }


//            'success' => $response->status() === 201,
//            'data' => json_decode($response->body(), true),
//        ];
    }
//
//    /**
//     * Store a newly created resource in storage.
//     *
//     * @param  \Illuminate\Http\Request  $request
//     * @return \Illuminate\Http\Response
//     */
//    public function store(Request $request)
//    {
//        //
//    }
//
//    /**
//     * Display the specified resource.
//     *
//     * @param  int  $id
//     * @return \Illuminate\Http\Response
//     */
//    public function show($id)
//    {
//        //
//    }
//
//    /**
//     * Show the form for editing the specified resource.
//     *
//     * @param  int  $id
//     * @return \Illuminate\Http\Response
//     */
//    public function edit($id)
//    {
//        //
//    }
//
////    /**
//     * Update the specified resource in storage.
//     *
//     * @param  \Illuminate\Http\Request  $request
//     * @param  int  $id
//     * @return \Illuminate\Http\Response
//
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
