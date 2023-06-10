<?php

namespace App\Traits;

use App\Models\Consultant;

use App\Models\BookingMessage;
use App\Models\ObjectBooking;
use App\Models\ObjectDetails;
use App\Models\ObjectWeekDays;
use App\Models\TimeSlot;
use App\Models\TimeSlotType;
use App\Models\User;
use App\Models\UserBookings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait IntegrationTrait
{
    use RepositoryTrait, Firebase;

    public function getObjectSlots(Request $request)
    {
        $repository = $this->getRepository("SlotRepository");
        if (is_null($request->object_id)) {
            if ($request->lang == "ar")
                $msg = "يرجى اختيار الطبيب.";
            else
                $msg = "The object field is required.";
            return response()->json([
                "status" => false,
                "message" => $msg
            ]);
        }
        if (is_null($request->date_from)) {
            if ($request->lang == "ar")
                $msg = "التاريخ من مطلوب.";
            else
                $msg = "The date from field is required.";
            return response()->json([
                "status" => false,
                "message" => $msg
            ]);
        }
        if (is_null($request->date_to)){
            if ($request->lang == "ar")
                $msg = "التاريخ إلى مطلوب.";
            else
                $msg = "The date to field is required.";
            return response()->json([
                "status" => false,
                "message" => $msg
            ]);
    }
        return $repository->objectSlots($request);
    }

    public function addNewBooking(Request $request)
    {
        $repository = $this->getRepository("ObjectBookingRepository");
        if (is_null($request->date)) {
            if ($request->lang == "ar")
                $msg = "التاريح مطلوب.";
            else
                $msg = "The date field is required.";
            return response()->json([
                "status" => false,
                "message" => $msg
            ]);
        }
        if (is_null($request->slot_id)) {
            if ($request->lang == "ar")
                $msg = "وقت الحجز مطلوب.";
            else
                $msg = "The slot id field is required.";
            return response()->json([
                "status" => false,
                "message" => $msg
            ]);
        }
        if (is_null($request->object_id)) {
            if ($request->lang == "ar")
                $msg = "يرجى اختيار الطبيب.";
            else
                $msg = "The object field is required.";
            return response()->json([
                "status" => false,
                "message" => $msg
            ]);
        }
        if (is_null($request->is_online)){
            if ($request->lang == "ar")
                $msg = "يرجى اختيار النوع.";
            else
                $msg = "The is online field is required.";
            return response()->json([
                "status" => false,
                "message" => $msg
            ]);
    }
        $date = Carbon::parse($request->date)->format('Y-m-d');
        $dayNumber = Carbon::parse($date)->dayOfWeek;
        // later point : we can remove this check to save time and improve speed
        $objectTimeSlotType = ObjectDetails::where("id", $request->object_id)->first()->time_slot_type_id;
        $checkDate = ObjectWeekDays::where([["time_slot_type_id", $objectTimeSlotType], ["week_day_number", $dayNumber]])->exists();
        if (!$checkDate)
            return response()->json([
                "status" => false,
                "message" => BookingMessage::where([["remark", 2], ["lang", $request->lang]])->first()->msg
            ]);
        $checkAvailabilty = ObjectBooking::where("date", $date)->where("slot_id", $request->slot_id)->where("object_id", $request->object_id)->where("is_cancelled", 0)->exists();
        if ($checkAvailabilty)
            return response()->json([
                "status" => false,
                "message" => BookingMessage::where([["remark", 3], ["lang", $request->lang]])->first()->msg
            ]);
        else {
            $reservationRecord = $repository->save($request);
//            return $reservationRecord;
            if ($request->is_online)
                return response()->json([
                    "status" => true,
                    "message" => BookingMessage::where([["remark", 4], ["lang", $request->lang]])->first()->msg,
                    "reservationRecordId" => $reservationRecord["reservationId"],
                    "zoomUrl" => $reservationRecord["zoom_url"]
                ]);
            else
                return response()->json([
                    "status" => true,
                    "message" => BookingMessage::where([["remark", 4], ["lang", $request->lang]])->first()->msg,
                    "reservationRecordId" => $reservationRecord["reservationId"]
                ]);
        }
    }

    public function getAllTimeSlotTypes()
    {
        $types = TimeSlotType::all();
        return response()->json([
            "status" => false,
            "data" => $types
        ]);
    }

    public function addNewObject(Request $request): \Illuminate\Http\JsonResponse
    {
        $repository = $this->getRepository("ObjectRepository");
        $objectId = $repository->save($request);
        return response()->json([
            "status" => true,
            "message" => "Objects added successfully",
            "objectId" => $objectId
        ]);
    }

    public function updateObject(Request $request): \Illuminate\Http\JsonResponse
    {
        $repository = $this->getRepository("ObjectRepository");
//        $repository->update($request);
        return response()->json([
            "status" => true,
            "message" => "Objects added successfully"
        ]);
    }

    public function deleteObject($id)
    {
        $object = ObjectDetails::find($id);
        $object->delete();
        return response()->json([
            "status" => true,
            "message" => "Objects deleted successfully"
        ]);
    }

    public function getObjectInfo(Request $request)
    {
        $repository = $this->getRepository("ObjectRepository");
        $objectId = Consultant::where("id", $request->id)->first()->object_id;
        $object = $repository->getById($objectId);
        return response()->json([
            "status" => true,
            "data" => $object
        ]);
    }

    public function getById($id)
    {
        $repository = $this->getRepository("ObjectBookingRepository");
        return $repository->getById($id);
    }

    public function consultantBookings(Request $request)
    {
        $userId = Auth::user()->id;
        $consultantInfo = Consultant::where("user_id", $userId)->first();
        $todayDate = date('Y-m-d');
        $todayTime = date('H:i:s');
        $isExist = 0;
        if ($request->status == "finished") {
            $bookings = ObjectBooking::where([["object_id", $consultantInfo->object_id],["is_cancelled", 0]])->get();
            for ($i = 0; $i < count($bookings); $i++) {
                $slot = TimeSlot::where("id", $bookings[$i]->slot_id)->first();
                if (($bookings[$i]->date < $todayDate) || ($bookings[$i]->date == $todayDate && $slot->time_to <= $todayTime)) {
                    $day = ObjectWeekDays::where("id", $slot->object_week_days_id)->first();
                    $isExist = 1;
                    $timeFrom = strtotime($slot->time_from);
                    $timeTo = strtotime($slot->time_to);
                    $newFormatFrom = date('H:i',$timeFrom);
                    $newFormatTo = date('H:i',$timeTo);
                    $bookings[$i]["date"] = $bookings[$i]->date;
                    $bookings[$i]["slot_from"] = $newFormatFrom;
                    $bookings[$i]["slot_to"] = $newFormatTo;
                    $bookings[$i]["day"] = $request->lang == "ar" ? $day->week_day_ar_name : $day->week_day_en_name;
                    $bookings[$i]["is_online"] = $bookings[$i]->is_online;
                    $userBooking = UserBookings::where("reservation_record_id", $bookings[$i]->reservation_record_id)->first();
                 $bookings[$i]["booking_id"] = $userBooking->id;
                    $user = User::where("id", $userBooking->user_id)->first();
                    $bookings[$i]["user_name"] = $user->full_name;
                    $bookings[$i]["user_phone_number"] = $user->phone_number;
                    unset($bookings[$i]["slot_id"]);
                    unset($bookings[$i]["object_id"]);
                    unset($bookings[$i]["created_at"]);
                    unset($bookings[$i]["updated_at"]);
                    $dataArray[] = $bookings[$i];
                }
            }
        } elseif ($request->status == "unfinished") {
            $bookings = ObjectBooking::where([["object_id", $consultantInfo->object_id],["is_cancelled", 0]])->get();
            for ($i = 0; $i < count($bookings); $i++) {
                $slot = TimeSlot::where("id", $bookings[$i]->slot_id)->first();
                if (($bookings[$i]->date > $todayDate) || ($bookings[$i]->date == $todayDate && $slot->time_to > $todayTime)) {

                    $day = ObjectWeekDays::where("id", $slot->object_week_days_id)->first();
                    $isExist = 1;
                    $timeFrom = strtotime($slot->time_from);
                    $timeTo = strtotime($slot->time_to);
                    $newFormatFrom = date('H:i',$timeFrom);
                    $newFormatTo = date('H:i',$timeTo);
                    $bookings[$i]["date"] = $bookings[$i]->date;
                    $bookings[$i]["slot_from"] = $newFormatFrom;
                    $bookings[$i]["slot_to"] = $newFormatTo;
                    $bookings[$i]["day"] = $request->lang == "ar" ? $day->week_day_ar_name : $day->week_day_en_name;
                    $bookings[$i]["is_online"] = $bookings[$i]->is_online;
                    $userBooking = UserBookings::where("reservation_record_id", $bookings[$i]->reservation_record_id)->first();
                    $bookings[$i]["booking_id"] = $userBooking->id;
                    $user = User::where("id", $userBooking->user_id)->first();
                    $bookings[$i]["user_name"] = $user->full_name;
                    $bookings[$i]["user_phone_number"] = $user->phone_number;
                    unset($bookings[$i]["slot_id"]);
                    unset($bookings[$i]["object_id"]);
                    unset($bookings[$i]["created_at"]);
                    unset($bookings[$i]["updated_at"]);
                    $dataArray[] = $bookings[$i];
                }
            }
        }
        elseif ($request->status == "cancelled") {
            $bookings = ObjectBooking::where([["object_id", $consultantInfo->object_id],["is_cancelled", 1]])->get();
            for ($i = 0; $i < count($bookings); $i++) {
                $slot = TimeSlot::where("id", $bookings[$i]->slot_id)->first();
                if (($bookings[$i]->is_cancelled == 1)) {
                    $day = ObjectWeekDays::where("id", $slot->object_week_days_id)->first();
                    $isExist = 1;
                    $timeFrom = strtotime($slot->time_from);
                    $timeTo = strtotime($slot->time_to);
                    $newFormatFrom = date('H:i',$timeFrom);
                    $newFormatTo = date('H:i',$timeTo);
                    $bookings[$i]["date"] = $bookings[$i]->date;
                    $bookings[$i]["slot_from"] = $newFormatFrom;
                    $bookings[$i]["slot_to"] = $newFormatTo;
                    $bookings[$i]["day"] = $request->lang == "ar" ? $day->week_day_ar_name : $day->week_day_en_name;
                    $bookings[$i]["is_online"] = $bookings[$i]->is_online;
                    $userBooking = UserBookings::where("reservation_record_id", $bookings[$i]->reservation_record_id)->first();
                    $bookings[$i]["booking_id"] = $userBooking->id;
                    $user = User::where("id", $userBooking->user_id)->first();
                    $bookings[$i]["user_name"] = $user->full_name;
                    $bookings[$i]["user_phone_number"] = $user->phone_number;
                    unset($bookings[$i]["slot_id"]);
                    unset($bookings[$i]["object_id"]);
                    unset($bookings[$i]["created_at"]);
                    unset($bookings[$i]["updated_at"]);
                    $dataArray[] = $bookings[$i];
                }
            }
        }
        if ($isExist == 1)
            return response()->json([
                "status" => true,
                "bookings" => $dataArray
            ]);
        else {
            $message = BookingMessage::where([["remark", 5], ["lang", $request->lang]])->first()->msg;
            return response()->json([
                "status" => true,
                "bookings" => $message
            ]);
        }

    }
    public function checkCancellation($bookingId){
        $booking = ObjectBooking::where("id", $bookingId)->first();
        if($booking->is_cancelled)
            return ["status" => false,  "message" => "This booking already cancelled"];
        $currentDate= date('Y-m-d');
        if($booking->date == $currentDate){
            return ["status" => false,  "message" => "You can't cancel booking on the same date"];
        }
        else{
            return ["status" => true,  "message" => "You can this cancel booking"];
        }
    }
}
