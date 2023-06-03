<?php

namespace App\Http\Controllers\Api\users;

use App\Http\Controllers\Controller;
use App\Models\BookingRating;
use App\Models\CancelledBooking;
use App\Models\Doctor;
use App\Models\BookingMessage;
use App\Models\ObjectBooking;
use App\Models\ObjectWeekDays;
use App\Models\OnlineBooking;
use App\Models\TimeSlot;
use App\Models\User;
use App\Models\UserBookings;
use App\Models\Notifications;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\IntegrationTrait;
use App\Traits\RepositoryTrait;
use App\Traits\Notifications as NotificationsTrait;
use App\Traits\Firebase;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    use IntegrationTrait;
    use RepositoryTrait;
    use NotificationsTrait;
    use Firebase;

    public function getUserBookings(Request $request)
    {
        $todayDate = date('Y-m-d');
        $todayTime = date('H:i:s');
        $userId = Auth::user()->id;
        $isExist = 0;
        $reservationRecordIdArr = UserBookings::where("user_id", $userId)->pluck("reservation_record_id");
        $bookingsArr = [];
        $dataArray = [];
        if ($request->status == "finished") {
            $bookings = ObjectBooking::whereIn("reservation_record_id", $reservationRecordIdArr)->where("is_cancelled", 0)->orderBy("date")->get();
            for($i=0; $i<count($bookings); $i++){
                $slot = TimeSlot::where("id", $bookings[$i]->slot_id)->first();
                if (($bookings[$i]->date < $todayDate) || ($bookings[$i]->date == $todayDate && $slot->time_to <= $todayTime)) {
                    $bookingsArr[$i]["reservation_record_id"] = $bookings[$i]->reservation_record_id;
                    $bookingsArr[$i]["id"] = UserBookings::where("reservation_record_id", $bookings[$i]->reservation_record_id)->first()->id;
                    $day = ObjectWeekDays::where("id", $slot->object_week_days_id)->first();
                    $isExist = 1;
                    $timeFrom = strtotime($slot->time_from);
                    $timeTo = strtotime($slot->time_to);
                    $newFormatFrom = date('H:i', $timeFrom);
                    $newFormatTo = date('H:i', $timeTo);
                    $bookingsArr[$i]["date"] = $bookings[$i]->date;
                    $bookingsArr[$i]["slot_from"] = $newFormatFrom;
                    $bookingsArr[$i]["slot_to"] = $newFormatTo;
                    $bookingsArr[$i]["day"] = $request->lang == "ar" ? $day->week_day_ar_name : $day->week_day_en_name;
                    $bookingsArr[$i]["is_online"] = $bookings[$i]->is_online;
                    $doctor = Doctor::where("object_id", $bookings[$i]->object_id)->first();
                    $bookingsArr[$i]["doctor_name"] = $doctor->full_name;
                    $bookingsArr[$i]["doctor_id"] = $doctor->id;
                    $imageData = $doctor->getFirstMediaFile("Doctors");
                    $bookingsArr[$i]["doctor_image"] = $imageData != null ? env("APP_PATH") . $imageData["path"] . "/" . $imageData["filename"] : null;
                    $dataArray[] = $bookingsArr[$i];
                }
            }
        }elseif ($request->status == "unfinished") {
            $bookings = ObjectBooking::whereIn("reservation_record_id", $reservationRecordIdArr)->where("is_cancelled", 0)->orderBy("date")->get();
            for($i=0; $i<count($bookings); $i++){
                $id = $bookings[$i]["id"];
                $slot = TimeSlot::where("id", $bookings[$i]->slot_id)->first();
                if (($bookings[$i]->date > $todayDate) || ($bookings[$i]->date == $todayDate && $slot->time_to > $todayTime)) {
                    $bookingsArr[$i]["reservation_record_id"] = $bookings[$i]->reservation_record_id;
                    $bookingsArr[$i]["id"] = UserBookings::where("reservation_record_id", $bookings[$i]->reservation_record_id)->first()->id;
                    $day = ObjectWeekDays::where("id", $slot->object_week_days_id)->first();
                    $isExist = 1;
                    $timeFrom = strtotime($slot->time_from);
                    $timeTo = strtotime($slot->time_to);
                    $newFormatFrom = date('H:i', $timeFrom);
                    $newFormatTo = date('H:i', $timeTo);
                    $bookingsArr[$i]["date"] = $bookings[$i]->date;
                    $bookingsArr[$i]["slot_from"] = $newFormatFrom;
                    $bookingsArr[$i]["slot_to"] = $newFormatTo;
                    $bookingsArr[$i]["day"] = $request->lang == "ar" ? $day->week_day_ar_name : $day->week_day_en_name;
                    $bookingsArr[$i]["is_online"] = $bookings[$i]->is_online;
                    $doctor = Doctor::where("object_id", $bookings[$i]->object_id)->first();
                    $bookingsArr[$i]["doctor_name"] = $doctor->full_name;
                    $bookingsArr[$i]["doctor_id"] = $doctor->id;
                    $imageData = $doctor->getFirstMediaFile("Doctors");
                    $bookingsArr[$i]["doctor_image"] = $imageData != null ? env("APP_PATH") . $imageData["path"] . "/" . $imageData["filename"] : null;
                    if($bookingsArr[$i]["is_online"]){
                        $time = Carbon::parse($newFormatFrom);
                        $timeBefore5Min = $time->subMinutes(5)->format('H:i:s');
                        $bookingsArr[$i]["zoom_url"] = OnlineBooking::where("booking_id", $id)->first()->zoom_url;
                        if($bookingsArr[$i]["date"] == $todayDate && $timeBefore5Min <= $todayTime)
                            $bookingsArr[$i]["start_meeting"] = 1;
                        else{
                            $bookingsArr[$i]["start_meeting"] = 1;
                            $bookingsArr[$i]["message"]  = BookingMessage::where([["remark", 6], ["lang", $request->lang]])->first()->msg;
                        }

                    }
                    $dataArray[] = $bookingsArr[$i];
                }
            }
        }elseif ($request->status == "cancelled") {
            $bookings = ObjectBooking::whereIn("reservation_record_id", $reservationRecordIdArr)->where("is_cancelled", 1)->orderBy("date")->get();
            for($i=0; $i<count($bookings); $i++){
                $bookingsArr[$i]["reservation_record_id"] = $bookings[$i]->reservation_record_id;
                $bookingsArr[$i]["id"] = UserBookings::where("reservation_record_id", $bookings[$i]->reservation_record_id)->first()->id;
                $slot = TimeSlot::where("id", $bookings[$i]->slot_id)->first();
                $day = ObjectWeekDays::where("id", $slot->object_week_days_id)->first();
                $isExist = 1;
                $timeFrom = strtotime($slot->time_from);
                $timeTo = strtotime($slot->time_to);
                $newFormatFrom = date('H:i', $timeFrom);
                $newFormatTo = date('H:i', $timeTo);
                $bookingsArr[$i]["date"] = $bookings[$i]->date;
                $bookingsArr[$i]["slot_from"] = $newFormatFrom;
                $bookingsArr[$i]["slot_to"] = $newFormatTo;
                $bookingsArr[$i]["day"] = $request->lang == "ar" ? $day->week_day_ar_name : $day->week_day_en_name;
                $bookingsArr[$i]["is_online"] = $bookings[$i]->is_online;
                $doctor = Doctor::where("object_id", $bookings[$i]->object_id)->first();
                $bookingsArr[$i]["doctor_name"] = $doctor->full_name;
                $bookingsArr[$i]["doctor_id"] = $doctor->id;
                $imageData = $doctor->getFirstMediaFile("Doctors");
                $bookingsArr[$i]["doctor_image"] = $imageData != null ? env("APP_PATH") . $imageData["path"] . "/" . $imageData["filename"] : null;
                $dataArray[] = $bookingsArr[$i];
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

    public function addBooking(Request $request)
    {
        return $this->addNewBooking($request);
    }

    public function getBookingDetails(Request $request)
    {
        return $this->getById($request);
    }
    public function getDoctorBookings(Request $request){
        return $this->doctorBookings($request);
    }
    public function getDoctorBookingDetails(Request $request)
    {
        $repository = $this->getRepository("ObjectBookingRepository");
        return $repository->getById($request);
    }
    public function cancelBooking(Request $request){
        DB::beginTransaction();
        try {
            $checkCancellation = $this->checkCancellation($request->id);
            if($checkCancellation["status"]) {
                $user = Auth::user();
                $booking = ObjectBooking::find($request->id);
                $booking->is_cancelled = 1;
                $booking->save();
                $cancelledBooking = new CancelledBooking();
                $cancelledBooking->booking_id = $request->id;
                $cancelledBooking->cancellation_reason = $request->reason;
                $cancelledBooking->cancellation_date = date('Y-m-d');
                $cancelledBooking->cancellation_time = date('H:i:s');
                $user->cancelledBooking()->save($cancelledBooking);

                $slotTime = TimeSlot::where("id", $booking->slot_id)->first();
                $timeFrom = strtotime($slotTime->time_from);
                $timeTo = strtotime($slotTime->time_to);
                $newFormatFrom = date('H:i', $timeFrom);
                $newFormatTo = date('H:i', $timeTo);
                $doctor = Doctor::where("object_id", $booking->object_id)->first();
                $userId = UserBookings::where("reservation_record_id", $booking->reservation_record_id)->first()->user_id;
                $user = User::where("id", $userId)->first();
                if (Auth::user()->id == $doctor->user_id)
                    $cancelledBy = $doctor->full_name;
                else
                    $cancelledBy = User::where("id", Auth::user()->id)->first()->full_name;
                $userNotificationMsg = $this->getNotificationTextDetails("cancel_booking", ["name" => $cancelledBy, "type" => $booking->is_online, "date" => $booking->date, "from" => $newFormatFrom, "to" => $newFormatTo]);
                $userNotificationTitle = $userNotificationMsg['title'][$user->notification_lang];
                $userNotificationBody = $userNotificationMsg['body'][$user->notification_lang];
                $userNotification = new Notifications();
                $userNotification->title = $userNotificationTitle;
                $userNotification->body = $userNotificationBody;
                $userNotification->user_id = $userId;
                $userNotification->type = 1;
                $userNotification->status = 1;
                $userNotification->save();
                $userDeviceToken = $user->device_token;
                $userDeviceTokens = $user->user_device_token->pluck("device_token")->toArray();
                array_push($userDeviceTokens, $userDeviceToken);
                $this->sendFirebaseNotificationCustom(["title" => $userNotificationTitle, "body" => $userNotificationBody], $userDeviceTokens);
                $userNot = Notifications::find($userNotification->id);
                $userNot->is_sent = 1;
                $userNot->save();

                $checkDoctor = User::where("id", $doctor->user_id);
                if($checkDoctor->exists()){
                    $doctorNotificationLang = User::where("id", $doctor->user_id)->first()->notification_lang;
                    $doctorNotificationMsg = $this->getNotificationTextDetails("cancel_booking", ["name" => $cancelledBy, "type" => $booking->is_online, "date" => $booking->date, "from" => $newFormatFrom, "to" => $newFormatTo]);
                    $doctorNotificationTitle = $doctorNotificationMsg['title'][$doctorNotificationLang];
                    $doctorNotificationBody = $doctorNotificationMsg['body'][$doctorNotificationLang];

                    $doctorNotification = new Notifications();
                    $doctorNotification->title = $doctorNotificationTitle;
                    $doctorNotification->body = $doctorNotificationBody;
                    $doctorNotification->user_id = $doctor->user_id;
                    $doctorNotification->type = 1;
                    $doctorNotification->status = 1;
                    $doctorNotification->save();
                    $checkDoctor = $checkDoctor->first();
                    $doctorDeviceToken = $checkDoctor->device_token;
                    $doctorDeviceTokens = $checkDoctor->user_device_token->pluck("device_token")->toArray();
                    array_push($doctorDeviceTokens,$doctorDeviceToken);
                    $this->sendFirebaseNotificationCustom(["title" => $doctorNotificationTitle, "body" => $doctorNotificationBody], $doctorDeviceTokens);
                    $doctorNot = Notifications::find($doctorNotification->id);
                    $doctorNot->is_sent = 1;
                    $doctorNot->save();
                }
                DB::commit();
                return response()->json([
                    "status" => true,
                    "message" => "Booking cancelled successfully"
                ]);
            }else
                return response()->json([
                    "status" => false,
                    "message" => $checkCancellation["message"]
                ]);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function addBookingRating(Request $request)
    {
        if (is_null($request->value)){
            if($request->lang == "ar")
                $msg = "قيمة التقييم مطلوبة.";
            else
                $msg = "The rating value field is required.";
            return response()->json([
                "status" => false,
                "message" => $msg
            ]);
        }

        $checkRating = BookingRating::where("user_booking_id", $request->booking_id)->exists();
        if($checkRating){
            if($request->lang == "ar")
                $msg = "لقد قمت بتقييم هذا الحجز مسبقاً";
            else
                $msg = "You have already rated this booking";
            return response()->json([
                "status" => false,
                "message" => $msg
            ]);
        }

        $rating = new BookingRating();
        $rating->rated_by = Auth::user()->id;
        $rating->rated_doctor = $request->doctor_id;
        $rating->user_booking_id = $request->booking_id;
        $rating->rating_value = $request->value;
        $rating->notes = $request->notes;
        $rating->save();
        return response()->json([
            "status" => true,
            "message" => "Rating added successfully"
        ]);
    }

}
