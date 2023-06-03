<?php

namespace App\Repositories;

use App\Models\BookingMessage;
use App\Models\BookingNotificationReminder;
use App\Models\BookingNotificationTime;
use App\Models\Doctor;
use App\Models\Notifications;
use App\Models\ObjectBooking;
use App\Models\ObjectDetails;
use App\Models\ObjectWeekDays;
use App\Models\OnlineBooking;
use App\Models\TimeSlot;
use App\Models\User;
use App\Models\UserBookings;
use App\Models\UserDeviceToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\Firebase;
use App\Traits\ZoomMeetingTrait;
use App\Traits\Notifications as NotificationsTrait;
use Illuminate\Support\Facades\DB;

class ObjectBookingRepository
{
    use Firebase;
    use ZoomMeetingTrait;
    use NotificationsTrait;
    const MEETING_TYPE_INSTANT = 1;
    const MEETING_TYPE_SCHEDULE = 2;
    const MEETING_TYPE_RECURRING = 3;
    const MEETING_TYPE_FIXED_RECURRING_FIXED = 8;

    public function rules(){
        return [
            "date" => ["required"],
            "slot_id" => ["required"],
            "object_id" => ["required"],
            "is_online" => ["required"]
        ];
    }

    public function getById($request){
        $userBooking = UserBookings::where("id", $request->id)->first();
        if(is_null($userBooking))
            return response()->json([
                "status" => false,
                "data" => "No booking with this id"
            ]);
        $booking = ObjectBooking::where("reservation_record_id", $userBooking->reservation_record_id)->first();
        $slot = TimeSlot::where("id", $booking->slot_id)->first();
        $day = ObjectWeekDays::where("id", $slot->object_week_days_id)->first();
        $booking["date"] = $booking->date;
        $timeFrom = strtotime($slot->time_from);
        $timeTo = strtotime($slot->time_to);
        $newFormatFrom = date('H:i',$timeFrom);
        $newFormatTo = date('H:i',$timeTo);
        $booking["slot_from"] = $newFormatFrom;
        $booking["slot_to"] = $newFormatTo;
        $booking["day"] = $request->lang == "ar" ? $day->week_day_ar_name : $day->week_day_en_name;
        $doctor = Doctor::where("object_id", $booking->object_id)->first();
        $booking["doctor_name"] = $doctor->full_name;
        $booking["doctor_id"] = $doctor->user_id;
        $user = User::where("id", $userBooking->user_id)->first();
        $booking["user_name"] = $user->full_name;
        $booking["user_phone_number"] = $user->phone_number;
        $booking["user_id"] = $user->id;
        if($booking->is_online){
            $currentDate = date('Y-m-d');
            $currentTime = date('H:i');
            $time = Carbon::parse($newFormatFrom);
            $timeBefore5Min = $time->subMinutes(5)->format('H:i');
            $booking["zoom_url"] = OnlineBooking::where("booking_id", $booking->id)->first()->zoom_url;
            if($booking->date == $currentDate && $timeBefore5Min <= $currentTime)
                $booking["start_meeting"] = 1;
            else{
                $booking["start_meeting"] = 0;
                $booking["message"]  = BookingMessage::where([["remark", 6], ["lang", $request->lang]])->first()->msg;
            }

        }
        unset($booking["slot_id"]);
        unset($booking["object_id"]);
        unset($booking["created_at"]);
        unset($booking["updated_at"]);
        return response()->json([
            "status" => true,
            "data" => $booking
        ]);
    }

    public function delete($booking){
        $booking->delete();
    }

    public function save(Request $request){
        DB::beginTransaction();
        try {
        $booking = new ObjectBooking();
        $date = Carbon::parse($request->date)->format('Y-m-d');
        $reservationRecordId = ObjectBooking::max("reservation_record_id");
        if(is_null($reservationRecordId))
            $reservationRecordId = 1;
        else{
            $reservationRecordId = $reservationRecordId + 1;
        }
        $booking->reservation_record_id = $reservationRecordId;
        $booking->date = $date;
        $booking->slot_id = $request->slot_id;
        $booking->object_id = $request->object_id;
        $booking->is_online = $request->is_online;
      if($booking->save()){
           $userBookings = new UserBookings();
           $userBookings->user_id = Auth::user()->id;
           $userBookings->reservation_record_id = $reservationRecordId;
           $userBookings->save();
           $slotTime = TimeSlot::where("id", $request->slot_id)->first();
           $timeFrom = strtotime($slotTime->time_from);
           $timeTo = strtotime($slotTime->time_to);
           $newFormatFrom = date('H:i',$timeFrom);
           $newFormatTo = date('H:i',$timeTo);
           $doctor = Doctor::where("object_id", $request->object_id)->first();
            $user = User::where("id", Auth::user()->id)->first();

           $userNotificationMsg = $this->getNotificationTextDetails("new_booking", ["type"=>$request->is_online, "date"=>$request->date, "from"=> $newFormatFrom, "to"=> $newFormatTo, "name"=>$doctor->full_name]);
           $userNotificationTitle = $userNotificationMsg['title'][$user->notification_lang];
           $userNotificationBody= $userNotificationMsg['body'][$user->notification_lang];

           $userNotification=new Notifications();
           $userNotification->title= $userNotificationTitle;
           $userNotification->body= $userNotificationBody;
           $userNotification->user_id=Auth::user()->id;
           $userNotification->type=1;
           $userNotification->status=1;
           $userNotification->save();
//           $userDevicetoken = $this->getTokens(User::findMany(Auth::user()->id));


           $userDeviceToken = $user->device_token;
           $userDeviceTokens = $user->user_device_token->pluck("device_token")->toArray();
           array_push($userDeviceTokens,$userDeviceToken);
//           return $userDeviceTokens;
//           dd($userDeviceTokens);
         //  $token = array_merge($userDevicetoken, $doctorDevicetoken);
        //   $otherUserDevicesToken = UserDeviceToken::where("user_id", Auth::user()->id)->orWhere("user_id", $doctorId)->get("device_token");
//           $otherUserDevicesToken = UserDeviceToken::where("user_id", Auth::user()->id)->get("device_token");
          /* return $otherUserDevicesToken;
           if(count($otherUserDevicesToken) > 0){
               for($i=0; $i<count($otherUserDevicesToken); $i++){
                   $userDevicetoken[] = $otherUserDevicesToken[$i]->device_token;
               }
           }*/
          $this->sendFirebaseNotificationCustom(["title"=>$userNotificationTitle,"body"=>$userNotificationBody],$userDeviceTokens);
           $userNot = Notifications::find($userNotification->id);
           $userNot->is_sent = 1;
           $userNot->save();

           $checkDoctor = User::where("id", $doctor->user_id);
           if($checkDoctor->exists()){
               $doctorNotificationLang = $checkDoctor->first()->notification_lang;
               $doctorNotificationMsg = $this->getNotificationTextDetails("new_booking", ["type"=>$request->is_online, "date"=>$request->date, "from"=> $newFormatFrom, "to"=> $newFormatTo, "name"=>$user->full_name]);
               $doctorNotificationTitle = $doctorNotificationMsg['title'][$doctorNotificationLang];
               $doctorNotificationBody= $doctorNotificationMsg['body'][$doctorNotificationLang];
               $doctorNotification=new Notifications();
               $doctorNotification->title= $doctorNotificationTitle;
               $doctorNotification->body= $doctorNotificationBody;
               $doctorNotification->user_id=$doctor->user_id;
               $doctorNotification->type=1;
               $doctorNotification->status=1;
               $doctorNotification->save();
               $checkDoctor = $checkDoctor->first();
               $doctorDeviceToken = $checkDoctor->device_token;
               $doctorDeviceTokens = $checkDoctor->user_device_token->pluck("device_token")->toArray();
               array_push($doctorDeviceTokens,$doctorDeviceToken);
              /* $doctorDevicetoken = $this->getTokens(User::findMany($doctor->user_id));
               $otherDoctorDevicesToken = UserDeviceToken::where("user_id", $doctor->user_id)->pluck("device_token");
               if(count($otherDoctorDevicesToken) > 0){
                   for($i=0; $i<count($otherDoctorDevicesToken); $i++){
                       $doctorDevicetoken[] = $otherDoctorDevicesToken[$i]->device_token;
                   }
               }*/
               $this->sendFirebaseNotificationCustom(["title"=>$doctorNotificationTitle,"body"=>$doctorNotificationBody],$doctorDeviceTokens);
               $doctorNot = Notifications::find($doctorNotification->id);
               $doctorNot->is_sent = 1;
               $doctorNot->save();
           }

           // add notification times for this booking to use it in cron job
           $times = BookingNotificationReminder::get();
           for($i=0; $i<count($times); $i++){
               if($times[$i]->duration_type == "Day"){
                   $userNotificationTime = new BookingNotificationTime();
                   $userNotificationTime->booking_id = $booking->id;
                   $userNotificationTime->notification_date =Carbon::parse($request->date)->subDays($times[$i]->duration_number)->format('Y-m-d');
                   $userNotificationTime->notification_time = $newFormatFrom;
                   $userNotificationTime->save();
               }elseif($times[$i]->duration_type == "Hour"){
                   $userNotificationTime = new BookingNotificationTime();
                   $userNotificationTime->booking_id = $booking->id;
                   $userNotificationTime->notification_date = $date;
                   $userNotificationTime->notification_time = Carbon::parse($slotTime->time_from)->subHours($times[$i]->duration_number)->format('H:i');
                   $userNotificationTime->save();
               }else{
                   $userNotificationTime = new BookingNotificationTime();
                   $userNotificationTime->booking_id = $booking->id;
                   $userNotificationTime->notification_date = $date;
                   $userNotificationTime->notification_time = Carbon::parse($slotTime->time_from)->subMinutes($times[$i]->duration_number)->format('H:i');
                   $userNotificationTime->save();
               }
           }
           // integrate and create new zoom room for online booking
           // reference : https://infyom.com/blog/how-to-integrate-zoom-meeting-apis-with-laravel
           if($request->is_online){
               $startTime =$date."T".$slotTime->time_from;
               $path = 'users/me/meetings';
               $response = $this->zoomPost($path, [
                   'topic' => "New Meeting",
                   'type' => self::MEETING_TYPE_SCHEDULE,
                   'start_time' => $this->toZoomTimeFormat($startTime),
                   'duration' => 30,
                   'agenda' => "agenda",
                   'timezone'     => 'Asia/Amman',
                   'settings' => [
                   //   'alternative_hosts' => 'rawan_almasri@digisolfze.com',
                       'host_video' => false,
                       'participant_video' => false,
                       'waiting_room' => false,
                   ]
               ]);
               $meetingData = json_decode($response->body(), true);
               $meeting = new OnlineBooking();
               $meeting->booking_id = $booking->id;
               $meeting->zoom_url = $meetingData["join_url"];
               $meeting->meeting_id = $meetingData["id"];
               $meeting->save();
               DB::commit();
               return ["reservationId" => $reservationRecordId, "zoom_url"=>$meetingData["join_url"]];
           }else{
               DB::commit();
               return ["reservationId" => $reservationRecordId];
           }

       }
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(Request $request){
        $booking = ObjectBooking::findOrFail($request->id);
        $booking->date = $request->date;
        $booking->slot_id = $request->slot_id;
        $booking->object_id = $request->object_id;
        $booking->save();
    }


}
