<?php

namespace App\job;

use App\Models\BookingNotificationReminder;
use App\Models\BookingNotificationTime;
use App\Models\Consultant;
use App\Models\Notifications;
use App\Models\ObjectBooking;
use App\Models\TimeSlot;
use App\Models\User;
use App\Models\UserBookings;
use App\Models\UserDeviceToken;
use App\Traits\Firebase;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Traits\Notifications as NotificationsTrait;

class BookingReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Firebase, NotificationsTrait;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      /*  $book = new BookingNotificationReminder();
        $current = Carbon::now();
        $book->duration_number = 55;
        $book->duration_type = "D";
     //   $book->time_in_mili_sec = $current->format("Y-m-d H:i:s.v");
        $book->save();*/

        $currentDate= date('Y-m-d');
        $currentTime= date('H:i');
        $timeAfter5Min = Carbon::now()->addMinutes(5)->format('H:i');
        $times = BookingNotificationTime::where([["notification_date", $currentDate], ["is_sent", 0]])->whereBetween('notification_time', [$currentTime, $timeAfter5Min])->get();
        for($i=0; $i<count($times); $i++){
            $booking = ObjectBooking::where("id", $times[$i]->booking_id)->first();
            $slotTime = TimeSlot::where("id", $booking->slot_id)->first();
            $timeFrom = strtotime($slotTime->time_from);
            $timeTo = strtotime($slotTime->time_to);
            $newFormatFrom = date('H:i',$timeFrom);
            $newFormatTo = date('H:i',$timeTo);
            $userId = UserBookings::where("reservation_record_id", $booking->reservation_record_id)->first()->user_id;
            $consultant = Consultant::where("object_id", $booking->object_id)->first();
            $user = User::where("id", $userId)->first();

            $consultantNotificationLang = User::where("id", $consultant->user_id)->first()->notification_lang;
            $userNotificationMsg = $this->getNotificationTextDetails("booking_reminder", ["type"=>$booking->is_online, "date"=>$booking->date, "from"=> $newFormatFrom, "to"=> $newFormatTo, "name"=>$consultant->full_name]);
            $consultantNotificationMsg = $this->getNotificationTextDetails("booking_reminder", ["type"=>$booking->is_online, "date"=>$booking->date, "from"=> $newFormatFrom, "to"=> $newFormatTo, "name"=>$user->full_name]);
            $userNotificationTitle = $userNotificationMsg['title'][$user->notification_lang];
            $userNotificationBody= $userNotificationMsg['body'][$user->notification_lang];
            $consultantNotificationTitle = $consultantNotificationMsg['title'][$consultantNotificationLang];
            $consultantNotificationBody= $consultantNotificationMsg['body'][$consultantNotificationLang];
            $userNotification=new Notifications();
            $userNotification->title= $userNotificationTitle;
            $userNotification->body= $userNotificationBody;
            $userNotification->user_id=$userId;
            $userNotification->type=1;
            $userNotification->status=1;
            $userNotification->save();

            $consultantNotification=new Notifications();
            $consultantNotification->title= $consultantNotificationTitle;
            $consultantNotification->body= $consultantNotificationBody;
            $consultantNotification->user_id=$consultant->user_id;
            $consultantNotification->type=1;
            $consultantNotification->status=1;
            $consultantNotification->save();
            $userDevicetoken = $this->getTokens(User::findMany($userId));
            $consultantDevicetoken = $this->getTokens(User::findMany($consultant->user_id));
            $otherUserDevicesToken = UserDeviceToken::where("user_id", $userId)->get("device_token");
            if(count($otherUserDevicesToken) > 0){
                for($j=0; $j<count($otherUserDevicesToken); $j++){
                    $userDevicetoken[] = $otherUserDevicesToken[$j]->device_token;
                }
            }
            $otherConsultantDevicesToken = UserDeviceToken::where("user_id", $consultant->user_id)->get("device_token");
            if(count($otherConsultantDevicesToken) > 0){
                for($k=0; $k<count($otherConsultantDevicesToken); $k++){
                    $consultantDevicetoken[] = $otherConsultantDevicesToken[$k]->device_token;
                }
            }
            $this->sendFirebaseNotificationCustom(["title"=>$userNotificationTitle,"body"=>$userNotificationBody],$userDevicetoken);
            $userNot = Notifications::find($userNotification->id);
            $userNot->is_sent = 1;
            $userNot->save();
            $this->sendFirebaseNotificationCustom(["title"=>$consultantNotificationTitle,"body"=>$consultantNotificationBody],$consultantDevicetoken);
            $consultantNot = Notifications::find($consultantNotification->id);
            $consultantNot->is_sent = 1;
            $consultantNot->save();
            $notificationTime = BookingNotificationTime::find($times[$i]->id);
            $notificationTime->is_sent = 1;
            $notificationTime->sent_date = Carbon::now()->format('Y-m-d');
            $notificationTime->sent_time = Carbon::now()->format('H:i:s');
            $notificationTime->notification_id = $notificationTime->user_id == $userId ? $userNotification->id : $consultantNotification->id;
            $notificationTime->save();
        }
    }
}
