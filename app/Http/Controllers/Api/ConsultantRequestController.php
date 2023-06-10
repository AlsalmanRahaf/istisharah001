<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\ConsultantRequest;
use App\Models\TimeSlotType;
use App\Models\ObjectWeekDays;
use Illuminate\Support\Facades\DB;



class ConsultantRequestController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        DB::beginTransaction();
        try {
            $consultant = new ConsultantRequest;
            $time_slot_type = new TimeSlotType();
//            json_decode( $req->request_type);
//            dd($type);
//                for($i=0; $i<count($type); $i++) {
//                    dd($type);
////                    $r = $consultant->type[$i];
//                }
//                   dd($r);


//dd(
//    $consultant->request_type =json_decode($req->request_type);
//    dd($consultant->request_type);
//            $type = json_decode($req->request_type);
//            for($i=0; $i<count($type); $i++){
//                $consultant->type[$i];
//                dd( $consultant->type[$i]);
//            }


            $consultant->name = $req->name;
            $consultant->email = $req->email;
            $consultant->phone = $req->phone;
            $consultant->online_price = $req->online_price;
            $consultant->offline_price = $req->offline_price;
            $consultant->request_type = $req->request_type;

             $consultant->save();
            if($req->hasfile("image")) {
                $consultant->saveMedia($req->file('image'), "Consultant Photo");

            }
            if($req->hasfile("document")) {
                for($i=0; $i<count($req->file('document')); $i++){
                    $consultant->saveMedia($req->file('document')[$i], "Consultant Documents");
                }

                }



            $time_slot_type->slot_duration = $req->slot_duration;
            $time_slot_type->consultant_id = $consultant->id;
            $time_slot_type->wating_time = $req->wating_time;
            $time_slot_type->save();


            $weekData = json_decode($req->week_days);
            for($i=0; $i<count($weekData); $i++){

                $object_week_day = new ObjectWeekDays();

                if($req->lang == "en"){

                    $en_name = $weekData[$i]->week_day_en_name;
                    $ar_name =Carbon::create($en_name)->locale('ar')->dayName ;


                }elseif ($req->lang=="ar"){

                    $ar_name = $weekData[$i]->week_day_ar_name;
                    $en_name  =Carbon::create($ar_name)->locale('en')->dayName;

                }

                $number=Carbon::parse($en_name)->dayOfWeek;


                $object_week_day->is_off = $weekData[$i]->is_off ;
                $object_week_day->week_day_number = $number;
                $object_week_day->time_slot_type_id =$time_slot_type->id ;


                $object_week_day->time_from = $weekData[$i]->time_from;


                $object_week_day->time_to = $weekData[$i]->time_to;


                $object_week_day->week_day_ar_name = $ar_name;


                $object_week_day->week_day_en_name = $en_name;

                $object_week_day->save();
            }



            $result = $object_week_day.$consultant. $time_slot_type ;

 DB::commit();
            if ($result) {
                return response()->json([
                   "status" => true,
                "message" => "Created Successfully"
                ]);
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "Something went error!"
                ]);
            }


        }catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
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
