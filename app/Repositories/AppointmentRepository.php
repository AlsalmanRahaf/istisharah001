<?php

namespace App\Repositories;

use App\Models\Admin;
use App\Models\BookingNotificationReminder;
use App\Models\BookingNotificationTime;
use App\Models\CancelledBooking;
use App\Models\Consultant;
use App\Models\Notifications;
use App\Models\ObjectBooking;
use App\Models\ObjectDetails;
use App\Models\ObjectWeekDays;
use App\Models\OnlineBooking;
use App\Models\TimeSlot;
use App\Models\User;
use App\Models\UserBookings;
use App\Models\UserDeviceToken;
use App\Traits\Firebase;
use App\Traits\IntegrationTrait;
use App\Traits\Notifications as NotificationsTrait;
use App\Traits\ZoomMeetingTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AppointmentRepository
{
    use IntegrationTrait;
    use Firebase;
    use ZoomMeetingTrait;
    use NotificationsTrait;
    const MEETING_TYPE_INSTANT = 1;
    const MEETING_TYPE_SCHEDULE = 2;
    const MEETING_TYPE_RECURRING = 3;
    const MEETING_TYPE_FIXED_RECURRING_FIXED = 8;
    use NotificationsTrait;
    use Firebase;
    public function rules(){
        return [
            'canselReason' => ['required']
        ];
    }
    public function index($request){
        if($request->statusId) {
            if ($request->statusId == 3) {
                $cancelledBooking = CancelledBooking::where('booking_id',$request->bookingId)->first();
                if ($cancelledBooking) {
                    $cancelledBooking->delete();
                }
                $booking = ObjectBooking::where('reservation_record_id',$request->bookingId)->first();
                $bookingAvailable = ObjectBooking::where([['date',$booking->date],['slot_id',$booking->slot_id],['object_id',$booking->object_id],['is_cancelled',0]])->count();
                if($bookingAvailable >= 1){
                    Session::flash('collision',"Can't return this reservation, because it's already booked by another user");
                    $booking->is_cancelled = 1;
                    $booking->save();
                }else{
                    $booking->is_cancelled = 0;
                    $booking->save();
                    $slotTime = TimeSlot::where("id", $booking->slot_id)->first();
                    $timeFrom = strtotime($slotTime->time_from);
                    $timeTo = strtotime($slotTime->time_to);
                    $newFormatFrom = date('H:i', $timeFrom);
                    $newFormatTo = date('H:i', $timeTo);
                    $consultant = Consultant::where("object_id", $booking->object_id)->first();
                    $userId = UserBookings::where("reservation_record_id", $booking->reservation_record_id)->first()->user_id;
                }

                $user = User::where("id", $userId)->first();
                if (Auth::user()->id == $consultant->user_id)
                    $returned = $consultant->full_name;
                else
                    $returned = Auth::user()->full_name;

                $consultantNotificationLang = User::where("id", $consultant->user_id)->first()->notification_lang;
                $userNotificationMsg = $this->getNotificationTextDetails("UnCancelled_booking", ["name" => $returned, "type" => $booking->is_online, "date" => $booking->date, "from" => $newFormatFrom, "to" => $newFormatTo]);
                $consultantNotificationMsg = $this->getNotificationTextDetails("UnCancelled_booking", ["name" => $returned, "type" => $booking->is_online, "date" => $booking->date, "from" => $newFormatFrom, "to" => $newFormatTo]);
                $userNotificationTitle = $userNotificationMsg['title'][$user->notification_lang];
                $userNotificationBody = $userNotificationMsg['body'][$user->notification_lang];
                $consultantNotificationTitle = $consultantNotificationMsg['title'][$consultantNotificationLang];
                $consultantNotificationBody = $consultantNotificationMsg['body'][$consultantNotificationLang];
                $userNotification = new Notifications();
                $userNotification->title = $userNotificationTitle;
                $userNotification->body = $userNotificationBody;
                $userNotification->user_id = $userId;
                $userNotification->type = 1;
                $userNotification->status = 1;
                $userNotification->save();
                $consultantNotification = new Notifications();
                $consultantNotification->title = $consultantNotificationTitle;
                $consultantNotification->body = $consultantNotificationBody;
                $consultantNotification->user_id = $consultant->user_id;
                $consultantNotification->type = 1;
                $consultantNotification->status = 1;
                $consultantNotification->save();
                $userDevicetoken = $this->getTokens(User::findMany($userId));
                $consultantDevicetoken = $this->getTokens(User::findMany($consultant->user_id));
                $otherUserDevicesToken = UserDeviceToken::where("user_id", $userId)->get("device_token");
                if (count($otherUserDevicesToken) > 0) {
                    for ($i = 0; $i < count($otherUserDevicesToken); $i++) {
                        $userDevicetoken[] = $otherUserDevicesToken[$i]->device_token;
                    }
                }
                $otherConsultantDevicesToken = UserDeviceToken::where("user_id", $consultant->user_id)->get("device_token");
                if (count($otherConsultantDevicesToken) > 0) {
                    for ($i = 0; $i < count($otherConsultantDevicesToken); $i++) {
                        $consultantDevicetoken[] = $otherConsultantDevicesToken[$i]->device_token;
                    }
                }
                $this->sendFirebaseNotificationCustom(["title" => $userNotificationTitle, "body" => $userNotificationBody], $userDevicetoken);
                $userNot = Notifications::find($userNotification->id);
                $userNot->is_sent = 1;
                $userNot->save();
                $this->sendFirebaseNotificationCustom(["title" => $consultantNotificationTitle, "body" => $consultantNotificationBody], $consultantDevicetoken);
                $consultantNot = Notifications::find($consultantNotification->id);
                $consultantNot->is_sent = 1;
                $consultantNot->save();
                Session::flash('success','The Appointment Status Change to Un Cancelled');
            }

            if ($request->statusId == 1) {
                $booking = ObjectBooking::where('reservation_record_id',$request->bookingId)->first();
                $booking->is_cancelled = 1;
                $booking->save();
                $cancelledBooking = CancelledBooking::find($request->bookingId);
                if ($cancelledBooking) {
                    $cancelledBooking->delete();
                }
                $admin = Auth::user();
                $cancelledBooking = new CancelledBooking();
                $cancelledBooking->booking_id = (int)$booking->id;
                $cancelledBooking->cancellation_reason = $request->canselReason;
                $cancelledBooking->cancellation_date = date('Y-m-d');
                $cancelledBooking->cancellation_time = date('H:i:s');
                $admin->cancelledBooking()->save($cancelledBooking);
                $slotTime = TimeSlot::where("id", $booking->slot_id)->first();
                $timeFrom = strtotime($slotTime->time_from);
                $timeTo = strtotime($slotTime->time_to);
                $newFormatFrom = date('H:i', $timeFrom);
                $newFormatTo = date('H:i', $timeTo);
                $consultant = Consultant::where("object_id", $booking->object_id)->first();
                $userId = UserBookings::where("reservation_record_id", $booking->reservation_record_id)->first()->user_id;
                $user = User::where("id", $userId)->first();
                if (Auth::user()->id == $consultant->user_id)
                    $cancelledBy = $consultant->full_name;
                else
                    $cancelledBy = Auth::user()->full_name;
                if ($consultant->user_id) {
                    $consultantNotificationLang = User::where("id", $consultant->user_id)->first()->notification_lang;
                    $userNotificationMsg = $this->getNotificationTextDetails("cancel_booking", ["name" => $cancelledBy, "type" => $booking->is_online, "date" => $booking->date, "from" => $newFormatFrom, "to" => $newFormatTo]);
                    $consultantNotificationMsg = $this->getNotificationTextDetails("cancel_booking", ["name" => $cancelledBy, "type" => $booking->is_online, "date" => $booking->date, "from" => $newFormatFrom, "to" => $newFormatTo]);
                    $userNotificationTitle = $userNotificationMsg['title'][$user->notification_lang];
                    $userNotificationBody = $userNotificationMsg['body'][$user->notification_lang];
                    $consultantNotificationTitle = $consultantNotificationMsg['title'][$consultantNotificationLang];
                    $consultantNotificationBody = $consultantNotificationMsg['body'][$consultantNotificationLang];
                    $userNotification = new Notifications();
                    $userNotification->title = $userNotificationTitle;
                    $userNotification->body = $userNotificationBody;
                    $userNotification->user_id = $userId;
                    $userNotification->type = 1;
                    $userNotification->status = 1;
                    $userNotification->save();
                    $consultantNotification = new Notifications();
                    $consultantNotification->title = $consultantNotificationTitle;
                    $consultantNotification->body = $consultantNotificationBody;
                    $consultantNotification->user_id = $consultant->user_id;
                    $consultantNotification->type = 1;
                    $consultantNotification->status = 1;
                    $consultantNotification->save();
                    $userDevicetoken = $this->getTokens(User::findMany($userId));
                    $consultantDevicetoken = $this->getTokens(User::findMany($consultant->user_id));
                    $otherUserDevicesToken = UserDeviceToken::where("user_id", $userId)->get("device_token");
                    if (count($otherUserDevicesToken) > 0) {
                        for ($i = 0; $i < count($otherUserDevicesToken); $i++) {
                            $userDevicetoken[] = $otherUserDevicesToken[$i]->device_token;
                        }
                    }
                    $otherConsultantDevicesToken = UserDeviceToken::where("user_id", $consultant->user_id)->get("device_token");
                    if (count($otherConsultantDevicesToken) > 0) {
                        for ($i = 0; $i < count($otherConsultantDevicesToken); $i++) {
                            $consultantDevicetoken[] = $otherConsultantDevicesToken[$i]->device_token;
                        }
                    }
                    $this->sendFirebaseNotificationCustom(["title" => $userNotificationTitle, "body" => $userNotificationBody], $userDevicetoken);
                    $userNot = Notifications::find($userNotification->id);
                    $userNot->is_sent = 1;
                    $userNot->save();
                    $this->sendFirebaseNotificationCustom(["title" => $consultantNotificationTitle, "body" => $consultantNotificationBody], $consultantDevicetoken);
                    $consultantNot = Notifications::find($consultantNotification->id);
                    $consultantNot->is_sent = 1;
                    $consultantNot->save();
                    Session::flash('success','The Appointment Status Change to Cancelled');
                    return redirect()->route('admin.date.index');
                }

            }
        }
    }
    public function update($request)
    {
        $dt = strtotime($request->user_object_booking);
        $days = date("l", $dt);
        $date = Carbon::parse($request->user_object_booking)->format('Y-m-d');
        $dayNumber = Carbon::parse($date)->dayOfWeek;
        $objectTimeSlotType = ObjectDetails::where("id", $request->object_id)->first();
        $weekDay = ObjectWeekDays::where([['time_slot_type_id', $objectTimeSlotType->time_slot_type_id], ['week_day_number', $dayNumber]])->first();
        $slotTime = TimeSlot::where([['object_week_days_id', $weekDay->id], ['time_from', $request->slotTime]])->first();
        $data['user_object_booking'] = ObjectBooking::where('reservation_record_id', $request->reservation_record_id)->first();
        $data['user_object_booking']->is_online = $request->is_online ?? $data['user_object_booking']->is_online;
        $data['user_object_booking']->slot_id = $slotTime->id ?? $data['user_object_booking']->slot_id;
        $data['user_object_booking']->object_id = $request->object_id ?? $data['user_object_booking']->object_id;
        $data['user_object_booking']->date = $request->user_object_booking ?? $data['user_object_booking']->date;
        $data['user_object_booking']->save();
        $slotTime = TimeSlot::find($data['user_object_booking']->slot_id);
        if ($data['user_object_booking']->is_online == 0) {
            $DirectBooking = OnlineBooking::where('booking_id', $request->reservation_record_id)->delete();
        } else {
            $startTime = $date . "T" . $slotTime->time_from;
            $path = 'users/me/meetings';
            $response = $this->zoomPost($path, [
                'topic' => "New Meeting",
                'type' => self::MEETING_TYPE_SCHEDULE,
                'start_time' => $this->toZoomTimeFormat($startTime),
                'duration' => 30,
                'agenda' => "agenda",
                'timezone' => 'Asia/Amman',
                'settings' => [
                    'host_video' => false,
                    'participant_video' => false,
                    'waiting_room' => false,
                ]
            ]);
            $meetingData = json_decode($response->body(), true);
            $meeting = new OnlineBooking();
            $meeting->booking_id = $data['user_object_booking']->id;
            $meeting->zoom_url = $meetingData["join_url"];
            $meeting->meeting_id = $meetingData["id"];
            $meeting->save();
        }
        $timeFrom = strtotime($slotTime->time_from);
        $timeTo = strtotime($slotTime->time_to);
        $newFormatFrom = date('H:i', $timeFrom);
        $newFormatTo = date('H:i', $timeTo);
        $consultantPrev = Consultant::where("object_id", $request->prev_object_id)->first();
        $consultant = Consultant::where("object_id", $data['user_object_booking']->object_id)->first();
        $userId = UserBookings::where("reservation_record_id", $data['user_object_booking']->reservation_record_id)->first()->user_id;
        if (!$userId) {
            return redirect()->route('admin.date.index');
        }
        if (Auth::user()->id == $consultant->user_id)
            $editdBy = $consultant->full_name;
        else
            $editdBy = Auth::user()->full_name;
        $user = User::where("id", $userId)->first();
        if ($consultant->user_id != $consultantPrev->user_id) {
            if ($request->oldConsultant !== "oldConsultant") {
                $user2NotificationMsg = $this->getNotificationTextDetails("get_booking", ["date" => $data['user_object_booking']->date, "from" => $newFormatFrom, "to" => $newFormatTo, "patient_name" => $user->full_name]);
                $user2NotificationTitle = $user2NotificationMsg['title'][$user->notification_lang];
                $user2NotificationBody = $user2NotificationMsg['body'][$user->notification_lang];
                $user2Notification = new Notifications();
                $user2Notification->title = $user2NotificationTitle;
                $user2Notification->body = $user2NotificationBody;
                $user2Notification->user_id = $consultantPrev->user_id;
                $user2Notification->type = 1;
                $user2Notification->status = 1;
                $user2Notification->save();
                $user2Devicetoken = $this->getTokens(User::findMany($consultantPrev->user_id));
                $otherUserDevicesToken = UserDeviceToken::where("user_id", $consultantPrev->user_id)->get("device_token");
                if (count($otherUserDevicesToken) > 0) {
                    for ($i = 0; $i < count($otherUserDevicesToken); $i++) {
                        $user2Devicetoken[] = $otherUserDevicesToken[$i]->device_token;
                    }
                }
                $this->sendFirebaseNotificationCustom(["title" => $user2NotificationTitle, "body" => $user2NotificationBody], $user2Devicetoken);
                $consultantNot = Notifications::find($user2Notification->id);
                $consultantNot->is_sent = 1;
                $consultantNot->save();
            }
            if ($request->newConsultant !== "newConsultant") {
                $user1NotificationMsg = $this->getNotificationTextDetails("transfer_booking", ["date" => $data['user_object_booking']->date, "from" => $newFormatFrom, "to" => $newFormatTo, "patient_name" => $user->full_name]);
                $user1NotificationTitle = $user1NotificationMsg['title'][$user->notification_lang];
                $user1NotificationBody = $user1NotificationMsg['body'][$user->notification_lang];
                $user1Notification = new Notifications();
                $user1Notification->title = $user1NotificationTitle;
                $user1Notification->body = $user1NotificationBody;
                $user1Notification->user_id = $consultant->user_id;
                $user1Notification->type = 1;
                $user1Notification->status = 1;
                $user1Notification->save();
                $user1Devicetoken = $this->getTokens(User::findMany($consultant->user_id));
                $otherConsultantDevicesToken = UserDeviceToken::where("user_id", $consultant->user_id)->get("device_token");
                if (count($otherConsultantDevicesToken) > 0) {
                    for ($i = 0; $i < count($otherConsultantDevicesToken); $i++) {
                        $user1Devicetoken[] = $otherConsultantDevicesToken[$i]->device_token;
                    }
                }
                $this->sendFirebaseNotificationCustom(["title" => $user1NotificationTitle, "body" => $user1NotificationBody], $user1Devicetoken);
                $userNot = Notifications::find($user1Notification->id);
                $userNot->is_sent = 1;
                $userNot->save();
            }
        }else{
            if (is_null($request->patient)) {
                $userNotificationMsg = $this->getNotificationTextDetails("Edit_booking", ["date" => $data['user_object_booking']->date, "from" => $newFormatFrom, "to" => $newFormatTo, "name" => $consultant->full_name]);
                $userNotificationTitle = $userNotificationMsg['title'][$user->notification_lang];
                $userNotificationBody = $userNotificationMsg['body'][$user->notification_lang];
                $userNotification = new Notifications();
                $userNotification->title = $userNotificationTitle;
                $userNotification->body = $userNotificationBody;
                $userNotification->user_id = $userId;
                $userNotification->type = 1;
                $userNotification->status = 1;
                $userNotification->save();
                $userDevicetoken = $this->getTokens(User::findMany($userId));
                $otherUserDevicesToken = UserDeviceToken::where("user_id", $userId)->get("device_token");
                if (count($otherUserDevicesToken) > 0) {
                    for ($i = 0; $i < count($otherUserDevicesToken); $i++) {
                        $userDevicetoken[] = $otherUserDevicesToken[$i]->device_token;
                    }
                }
                $this->sendFirebaseNotificationCustom(["title" => $userNotificationTitle, "body" => $userNotificationBody], $userDevicetoken);
                $userNot = Notifications::find($userNotification->id);
                $userNot->is_sent = 1;
                $userNot->save();
            }

            $consultantNotificationLang = User::where("id", $consultant->user_id)->first()->notification_lang;
            if (is_null($request->Consultant)) {
                $consultantNotificationMsg = $this->getNotificationTextDetails("Edit_booking_Consultant", ["date" => $data['user_object_booking']->date, "from" => $newFormatFrom, "to" => $newFormatTo, "name" => $consultant->full_name]);
                $consultantNotificationTitle = $consultantNotificationMsg['title'][$consultantNotificationLang];
                $consultantNotificationBody = $consultantNotificationMsg['body'][$consultantNotificationLang];
                $consultantNotification = new Notifications();
                $consultantNotification->title = $consultantNotificationTitle;
                $consultantNotification->body = $consultantNotificationBody;
                $consultantNotification->user_id = $consultant->user_id;
                $consultantNotification->type = 1;
                $consultantNotification->status = 1;
                $consultantNotification->save();
                $consultantDevicetoken = $this->getTokens(User::findMany($consultant->user_id));
                $otherConsultantDevicesToken = UserDeviceToken::where("user_id", $consultant->user_id)->get("device_token");
                if (count($otherConsultantDevicesToken) > 0) {
                    for ($i = 0; $i < count($otherConsultantDevicesToken); $i++) {
                        $consultantDevicetoken[] = $otherConsultantDevicesToken[$i]->device_token;
                    }
                }
                $this->sendFirebaseNotificationCustom(["title" => $consultantNotificationTitle, "body" => $consultantNotificationBody], $consultantDevicetoken);
                $consultantNot = Notifications::find($consultantNotification->id);
               $consultantNot->is_sent = 1;
               $consultantNot->save();
            }

        }
        $this->notificationReminders($data['user_object_booking']->id, $data['user_object_booking']->date);

        Session::flash('edit_success','Edit Appointment Successfully for '.$user->full_name);

        return redirect()->route('admin.date.index');
    }
    public function notificationReminders($objectBooking,$objectBookingDate){
        $booking_notification_reminders = BookingNotificationReminder::all();
        $objectBookingData = ObjectBooking::find($objectBooking);
        $slotData = TimeSlot::find($objectBookingData->slot_id);

        $day = 86400;
        $hour = 3600;
        $minutes = 60;

        foreach($booking_notification_reminders as $index => $reminder) {
            $duration_type = $reminder->duration_type;
            $duration_number = $reminder->duration_number;
            if ($duration_type == "Minute"){
                $notification_reminders_in_seconds = $duration_number * $minutes;
                $this->objectBookingCarbon[$index]['notification_date'][] = $objectBookingDate;
            }elseif ($duration_type == "Hour"){
                $notification_reminders_in_seconds = $duration_number * $hour;
                $this->objectBookingCarbon[$index]['notification_date'][] = $objectBookingDate;
                if ($duration_number > 24){
                    $this->objectBookingCarbon[$index]['notification_date'][] = Carbon::parse($objectBookingDate)->subSeconds($notification_reminders_in_seconds)->format('Y-m-d');
                }
            }else {
                $notification_reminders_in_seconds = $duration_number * $day;
                $this->objectBookingCarbon[$index]['notification_date'][] = Carbon::parse($objectBookingDate)->subSeconds($notification_reminders_in_seconds)->format('Y-m-d');
            }
            $this->objectBookingCarbon[$index]['notification_time'][] = Carbon::parse($slotData->time_from)->subSeconds($notification_reminders_in_seconds)->format('H:i:s');
        }
        $this->addNotificationReminders($objectBooking,$this->objectBookingCarbon);
    }
    public function addNotificationReminders($id,$objectBookingCarbon){
        $objectBookingTime =BookingNotificationTime::where('booking_id',$id)->get();
        foreach ($objectBookingTime as $index => $Carbon){
            $Carbon->booking_id = $id;
            $Carbon->notification_date = $objectBookingCarbon[$index]['notification_date'][0];
            $Carbon->notification_time = $objectBookingCarbon[$index]['notification_time'][0];
            $Carbon->is_sent = 0;
            $Carbon->sent_date = null;
            $Carbon->sent_time = null;
            $Carbon->notification_id = null;
            $Carbon->save();
        }
    }
}
