<?php

namespace App\Http\Livewire;
use App\Models\CancelledBooking;
use App\Models\Doctor;
use App\Models\Notifications;
use App\Models\ObjectBooking;
use App\Models\ObjectDetails;
use App\Models\ObjectWeekDays;
use App\Models\TimeSlot;
use App\Models\TimeSlotType;
use App\Models\User;
use App\Models\UserBookings;
use App\Models\UserDeviceToken;
use App\Traits\Firebase;
use App\Traits\IntegrationTrait;
use App\Traits\ZoomMeetingTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Traits\Notifications as NotificationsTrait;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Illuminate\Http\Request;

class Cancellation extends Component
{
    use IntegrationTrait;
    use Firebase;
    use ZoomMeetingTrait;
    use NotificationsTrait;
    const MEETING_TYPE_INSTANT = 1;
    const MEETING_TYPE_SCHEDULE = 2;
    const MEETING_TYPE_RECURRING = 3;
    const MEETING_TYPE_FIXED_RECURRING_FIXED = 8;
    public $is_cancelled,
           $cancelled_reason,
           $reservation_record_id,
           $cancellationStatus;
    public function mount(){
        $this->cancellationStatus = $this->is_cancelled;
    }
    public function changeCancellation(){
        if($this->cancellationStatus) {
            if ($this->cancellationStatus === "3") {
                $cancelledBooking = CancelledBooking::where('booking_id',$this->reservation_record_id)->first();
                if ($cancelledBooking) {
                    $cancelledBooking->delete();
                }
                $booking = ObjectBooking::where('reservation_record_id',$this->reservation_record_id)->first();
                $bookingAvailable = ObjectBooking::where([['date',$booking->date],['slot_id',$booking->slot_id],['object_id',$booking->object_id]])->count();
                if($bookingAvailable > 1){
                    Session::flash('collision','Cant UnCancelled Reservation,The Reservation is available');
                    $booking->is_cancelled = 1;
                    $booking->save();
                }else {
                    $booking->is_cancelled = 0;
                    $booking->save();
                    $slotTime = TimeSlot::where("id", $booking->slot_id)->first();
                    $timeFrom = strtotime($slotTime->time_from);
                    $timeTo = strtotime($slotTime->time_to);
                    $newFormatFrom = date('H:i', $timeFrom);
                    $newFormatTo = date('H:i', $timeTo);
                    $doctor = Doctor::where("object_id", $booking->object_id)->first();
                    $userId = UserBookings::where("reservation_record_id", $booking->reservation_record_id)->first()->user_id;
                    $user = User::where("id", $userId)->first();
                    if (Auth::user()->id == $doctor->user_id)
                        $activeBy = $doctor->full_name;
                    else
                        $activeBy = Auth::user()->full_name;

                    $doctorNotificationLang = User::where("id", $doctor->user_id)->first()->notification_lang;
                    $userNotificationMsg = $this->getOrderNotificationDetails("UnCancelled_booking", ["name" => $activeBy, "type" => $booking->is_online, "date" => $booking->date, "from" => $newFormatFrom, "to" => $newFormatTo]);
                    $doctorNotificationMsg = $this->getOrderNotificationDetails("UnCancelled_booking", ["name" => $activeBy, "type" => $booking->is_online, "date" => $booking->date, "from" => $newFormatFrom, "to" => $newFormatTo]);
                    $userNotificationTitle = $userNotificationMsg['title'][$user->notification_lang];
                    $userNotificationBody = $userNotificationMsg['body'][$user->notification_lang];
                    $doctorNotificationTitle = $doctorNotificationMsg['title'][$doctorNotificationLang];
                    $doctorNotificationBody = $doctorNotificationMsg['body'][$doctorNotificationLang];
                    $userNotification = new Notifications();
                    $userNotification->title = $userNotificationTitle;
                    $userNotification->body = $userNotificationBody;
                    $userNotification->user_id = $userId;
                    $userNotification->type = 1;
                    $userNotification->status = 1;
                    $userNotification->save();
                    $doctorNotification = new Notifications();
                    $doctorNotification->title = $doctorNotificationTitle;
                    $doctorNotification->body = $doctorNotificationBody;
                    $doctorNotification->user_id = $doctor->user_id;
                    $doctorNotification->type = 1;
                    $doctorNotification->status = 1;
                    $doctorNotification->save();
                    $userDevicetoken = $this->getTokens(User::findMany($userId));
                    $doctorDevicetoken = $this->getTokens(User::findMany($doctor->user_id));
                    $otherUserDevicesToken = UserDeviceToken::where("user_id", $userId)->get("device_token");
                    if (count($otherUserDevicesToken) > 0) {
                        for ($i = 0; $i < count($otherUserDevicesToken); $i++) {
                            $userDevicetoken[] = $otherUserDevicesToken[$i]->device_token;
                        }
                    }
                    $otherDoctorDevicesToken = UserDeviceToken::where("user_id", $doctor->user_id)->get("device_token");
                    if (count($otherDoctorDevicesToken) > 0) {
                        for ($i = 0; $i < count($otherDoctorDevicesToken); $i++) {
                            $doctorDevicetoken[] = $otherDoctorDevicesToken[$i]->device_token;
                        }
                    }
                    $this->sendFirebaseNotificationCustom(["title" => $userNotificationTitle, "body" => $userNotificationBody], $userDevicetoken);
                    $userNot = Notifications::find($userNotification->id);
                    $userNot->is_sent = 1;
                    $userNot->save();
                    $this->sendFirebaseNotificationCustom(["title" => $doctorNotificationTitle, "body" => $doctorNotificationBody], $doctorDevicetoken);
                    $doctorNot = Notifications::find($doctorNotification->id);
                    $doctorNot->is_sent = 1;
                    $doctorNot->save();
                }
            }
            if ($this->cancellationStatus == 1) {

                $booking = ObjectBooking::where('reservation_record_id',$this->reservation_record_id)->first();
                $booking->is_cancelled = 1;
                $booking->save();
                $cancelledBooking = CancelledBooking::find($this->reservation_record_id);
                if ($cancelledBooking) {
                    $cancelledBooking->delete();
                }
                $cancelledBooking = new CancelledBooking();
                $cancelledBooking->booking_id = (int)$booking->reservation_record_id;
                $cancelledBooking->cancelled_by = Auth::user()->id;
                $cancelledBooking->cancellation_reason = $this->cancelled_reason;
                $cancelledBooking->cancellation_date = date('Y-m-d');
                $cancelledBooking->cancellation_time = date('H:i:s');
                $cancelledBooking->save();
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
                    $cancelledBy = Auth::user()->full_name;
                if ($doctor->user_id) {
                    $doctorNotificationLang = User::where("id", $doctor->user_id)->first()->notification_lang;
                    $userNotificationMsg = $this->getOrderNotificationDetails("cancel_booking", ["name" => $cancelledBy, "type" => $booking->is_online, "date" => $booking->date, "from" => $newFormatFrom, "to" => $newFormatTo]);
                    $doctorNotificationMsg = $this->getOrderNotificationDetails("cancel_booking", ["name" => $cancelledBy, "type" => $booking->is_online, "date" => $booking->date, "from" => $newFormatFrom, "to" => $newFormatTo]);
                    $userNotificationTitle = $userNotificationMsg['title'][$user->notification_lang];
                    $userNotificationBody = $userNotificationMsg['body'][$user->notification_lang];
                    $doctorNotificationTitle = $doctorNotificationMsg['title'][$doctorNotificationLang];
                    $doctorNotificationBody = $doctorNotificationMsg['body'][$doctorNotificationLang];
                    $userNotification = new Notifications();
                    $userNotification->title = $userNotificationTitle;
                    $userNotification->body = $userNotificationBody;
                    $userNotification->user_id = $userId;
                    $userNotification->type = 1;
                    $userNotification->status = 1;
                    $userNotification->save();
                    $doctorNotification = new Notifications();
                    $doctorNotification->title = $doctorNotificationTitle;
                    $doctorNotification->body = $doctorNotificationBody;
                    $doctorNotification->user_id = $doctor->user_id;
                    $doctorNotification->type = 1;
                    $doctorNotification->status = 1;
                    $doctorNotification->save();
                    $userDevicetoken = $this->getTokens(User::findMany($userId));
                    $doctorDevicetoken = $this->getTokens(User::findMany($doctor->user_id));
                    $otherUserDevicesToken = UserDeviceToken::where("user_id", $userId)->get("device_token");
                    if (count($otherUserDevicesToken) > 0) {
                        for ($i = 0; $i < count($otherUserDevicesToken); $i++) {
                            $userDevicetoken[] = $otherUserDevicesToken[$i]->device_token;
                        }
                    }
                    $otherDoctorDevicesToken = UserDeviceToken::where("user_id", $doctor->user_id)->get("device_token");
                    if (count($otherDoctorDevicesToken) > 0) {
                        for ($i = 0; $i < count($otherDoctorDevicesToken); $i++) {
                            $doctorDevicetoken[] = $otherDoctorDevicesToken[$i]->device_token;
                        }
                    }
                    $this->sendFirebaseNotificationCustom(["title" => $userNotificationTitle, "body" => $userNotificationBody], $userDevicetoken);
                    $userNot = Notifications::find($userNotification->id);
                    $userNot->is_sent = 1;
                    $userNot->save();
                    $this->sendFirebaseNotificationCustom(["title" => $doctorNotificationTitle, "body" => $doctorNotificationBody], $doctorDevicetoken);
                    $doctorNot = Notifications::find($doctorNotification->id);
                    $doctorNot->is_sent = 1;
                    $doctorNot->save();
                }

            }
        }
    }
    public function save(){
        $this->cancelled_reason = $this->cancelled_reason;
    }
    public function render()
    {
        return view('livewire.cancellation');
    }
}
