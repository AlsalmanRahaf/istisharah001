<?php

namespace App\Repositories;

use App\Models\BookingMessage;
use App\Models\ObjectBooking;
use App\Models\ObjectDetails;
use App\Models\ObjectWeekDays;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SlotRepository
{
    public function rules(){
        return [
            "date_from" => ["required"],
            "date_to" => ["required"],
            "object_id" => ["required"]
        ];
    }

    public function availableSlots(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->all();
        // get object time slot type
        $timeSlotType = ObjectDetails::where("id", $data['object_id'])->first()->time_slot_type_id;
        $dateFrom = (new Carbon($data['date_from']))->toDateString(); // change date format
        $dateTo = (new Carbon($data['date_to']))->toDateString();
        $datetime1 = new \DateTime($dateFrom); // convert date from string to date
        $datetime2 = new \DateTime($dateTo);
        // get number of days between two dates
        $interval = $datetime1->diff($datetime2);
        $days = $interval->format('%a');
        $dataArr = [];
        $errorArr = ["status" => false, "message" => ""]; // declare array to save status and msg in each loop
        for($i=0; $i<=$days; $i++){
            $dayNumber = Carbon::parse($dateFrom)->dayOfWeek; // get number of day for date
            $checkObjectWeekDays = ObjectWeekDays::where("time_slot_type_id", $timeSlotType)->where("week_day_number", $dayNumber);
            if(!$checkObjectWeekDays->exists()) { // check if this object has slots on this date based on time slot type
                $errorArr["message"] =  BookingMessage::where([["remark", 1], ["lang", $request->lang]])->first()->msg; // check if return number of slots = 0 instead
                $dataArr[$dateFrom] = $errorArr; // save date as key and error array as value
            }
            else{
                $objectWeekDays = $checkObjectWeekDays->first()->id;
                $timeSlots = TimeSlot::where("object_week_days_id", $objectWeekDays)->get();
                $dataArr[$dateFrom] = $timeSlots;
            }
            $dateFrom = (new Carbon($dateFrom))->addDays(1)->toDateString();// get the next day
            if($dateFrom > $dateTo) // check if next day exceeded the date_to
                break;
        }
        return response()->json([
            "status" => true,
            "schedule" =>   $dataArr
        ]);
    }

    public function bookedSlots(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->all();
        $dateFrom = (new Carbon($data['date_from']))->toDateString(); // change date format
        $dateTo = (new Carbon($data['date_to']))->toDateString();
        $datetime1 = new \DateTime($dateFrom); // convert date from string to date
        $datetime2 = new \DateTime($dateTo);
        // get number of days between two dates
        $interval = $datetime1->diff($datetime2);
        $days = $interval->format('%a');
        $dataArr = [];
        $errorArr = ["status" => false, "message" => ""];
        for($i=0; $i<=$days; $i++){
            $slots = ObjectBooking::where([["date", $dateFrom], ["object_id", $data["object_id"]]]);
            $checkSlots = $slots->exists();
            if(!$checkSlots){
                $errorArr["message"] =  "There is no bookings on this date";
                $dataArr[$dateFrom] = $errorArr;
            }
            else{
                $bookedSlots = $slots->get();
                $dataArr[$dateFrom] = $bookedSlots;
            }
            $dateFrom = (new Carbon($dateFrom))->addDays(1)->toDateString();
            if($dateFrom > $dateTo)
                break;
        }
        return response()->json([
            "status" => true,
            "schedule" =>   $dataArr
        ]);
    }
    public function objectSlots(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->all();
        $todayDate = date('Y-m-d');
       $todayTime = date('H:i:s');
        // get object time slot type
        $timeSlotType = ObjectDetails::where("id", $data['object_id'])->first()->time_slot_type_id;
        $dateFrom = (new Carbon($data['date_from']))->toDateString(); // change date format
        $dateTo = (new Carbon($data['date_to']))->toDateString();
        $datetime1 = new \DateTime($dateFrom); // convert date from string to date
        $datetime2 = new \DateTime($dateTo);
        // get number of days between two dates
        $interval = $datetime1->diff($datetime2);
        $days = $interval->format('%a');
        $dataArr = [];
        $res = [];
        for($i=0; $i<=$days; $i++){
            $dayNumber = Carbon::parse($dateFrom)->dayOfWeek; // get number of day for date
            $day = ObjectWeekDays::where("week_day_number", $dayNumber);
            $dayName = $request->lang == "ar" ? $day->first()->week_day_ar_name : $day->first()->week_day_en_name;
            $checkObjectWeekDays = $day->where("time_slot_type_id", $timeSlotType);
            if(!$checkObjectWeekDays->exists()) { // check if this object has slots on this date based on time slot type
                $dataArr["daytime"] = ["day" => $dayName , "date" => $dateFrom , "data" => []];
                $res[] = $dataArr;
            }
            if($checkObjectWeekDays->exists()) {
                $objectWeekDays = $checkObjectWeekDays->first()->id;
                $timeSlots = TimeSlot::where("object_week_days_id", $objectWeekDays)->get(["id", "time_from", "time_to", "description"]);
                if (count($timeSlots) > 0){
                    for ($j = 0; $j < count($timeSlots); $j++) {
                        $timeFrom = strtotime($timeSlots[$j]["time_from"]);
                        $timeTo = strtotime($timeSlots[$j]["time_to"]);
                        $newFormatFrom = date('H:i',$timeFrom);
                        $newFormatTo = date('H:i',$timeTo);
                        $timeSlots[$j]["time_from"] = $newFormatFrom;
                        $timeSlots[$j]["time_to"] = $newFormatTo;
                        if (ObjectBooking::where([["date", $dateFrom], ["slot_id", $timeSlots[$j]["id"]], ["object_id", $data['object_id']], ["is_cancelled", 0]])->exists())
                            $timeSlots[$j]["available"] = 0;
                        else{
                            if($dateFrom == $todayDate && $timeSlots[$j]["time_from"] < $todayTime)
                            $timeSlots[$j]["available"] = 0;
                            else
                                $timeSlots[$j]["available"] = 1;
                        }

                    }
                $dataArr["daytime"] = ["day" => $dayName, "date" => $dateFrom, "data" => $timeSlots];
                    $res[] = $dataArr;
            }

             //   $dataArr[$dateFrom] = $timeSlots;
            }
            $dateFrom = (new Carbon($dateFrom))->addDays(1)->toDateString();// get the next day
            if($dateFrom > $dateTo) // check if next day exceeded the date_to
                break;
        }
        return response()->json([
            "status" => true,
            "schedule" =>  $res
        ]);
    }
}
