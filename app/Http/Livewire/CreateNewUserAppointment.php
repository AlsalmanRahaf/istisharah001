<?php

namespace App\Http\Livewire;
use App\Models\BookingNotificationReminder;
use App\Models\BookingNotificationTime;
use App\Models\Doctor;
use App\Models\Notifications;
use App\Models\ObjectBooking;
use App\Models\ObjectDetails;
use App\Models\ObjectWeekDays;
use App\Models\OnlineBooking;
use App\Models\TimeSlot;
use App\Models\TimeSlotType;
use App\Models\User;
use App\Models\UserBookings;
use App\Models\UserDeviceToken;
use App\Traits\Firebase;
use App\Traits\IntegrationTrait;
use App\Traits\Notifications as NotificationsTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Traits\ZoomMeetingTrait;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Http\Request;


class CreateNewUserAppointment extends Component
{
    use IntegrationTrait;
    use Firebase;
    use ZoomMeetingTrait;
    use NotificationsTrait;
    const MEETING_TYPE_INSTANT = 1;
    const MEETING_TYPE_SCHEDULE = 2;
    const MEETING_TYPE_RECURRING = 3;
    const MEETING_TYPE_FIXED_RECURRING_FIXED = 8;
    public $full_name,
           $phone,
           $users,
           $object_id,
           $is_online,
           $user_object_booking,
           $slotTime,
           $doctors,
           $doct,
           $data,
           $slot_time,
           $days,
           $doctor,
           $time,
           $week_day,
           $object_week_days,
           $date,
           $flag = false,
           $createFlag = false,
           $objectTimeSlotType,
           $dayNumber,
           $user,
           $dis_data,
           $newData,
           $objectBookingCarbon,
           $disable_time,
           $arrayFilter=[],
           $dt,
           $collection;
    public $isSetClicked = false;

    protected function rules()
    {
        return [
            'full_name' => 'required|min:6',
            'phone' => 'required|string|min:9|max:9|unique:users,phone_number',
            'object_id' => 'required',
            'is_online' => 'required',
            'user_object_booking' => 'required',
            'slotTime' => 'required',
        ];
    }
    public function updated($fields)
    {
        $this->validateOnly($fields);
    }
    public function onlineOrNot(){
        $this->doct = null;
        $this->newData = null;
        $this->dis_data = null;
        $this->arrayFilter = null;
        $this->slot_time= null;
        $this->flag = false;
        $this->createFlag = false;

        if($this->is_online == 1){
            $this->OnlineDoctorData();
        }else{
            $this->OfflineDoctorData();
        }
    }
    public function SetClicked(Request $request)
    {
        $this->onlineOrNot();
        $this->handleTime($request);
        $this->isSetClicked == false ? $this->isSetClicked = true : $this->isSetClicked = false;
    }

    public function handleTime(Request $request){
        if ($this->object_id && $this->user_object_booking){
            $this->flag = true;
        }
        $this->createFlag = false;
        $this->collection = null;
        $this->data = null;
        $this->newData = null;
        $this->dis_data = null;
        $this->slotTime = null;
        $this->arrayFilter = null;
        $this->slot_time= null;
        if ($this->object_id) {
            $this->dt = strtotime($this->user_object_booking);
            $this->days = date("l", $this->dt);
            $this->date = Carbon::parse($this->user_object_booking)->format('Y-m-d');
            $this->dayNumber = Carbon::parse($this->date)->dayOfWeek;
            $this->objectTimeSlotType = ObjectDetails::where("id", $this->object_id)->first();
            $checkDate = ObjectWeekDays::where([["time_slot_type_id", $this->objectTimeSlotType->time_slot_type_id], ["week_day_number", $this->dayNumber]])->exists();
            if ($checkDate) {
                $this->doctors = Doctor::where('object_id', '=', $this->object_id)->first();
                $this->time = ObjectDetails::where('description', $this->doctors->full_name)->first();
                if ($this->time) {
                    $this->week_day = ObjectWeekDays::where('time_slot_type_id', $this->time->time_slot_type_id)->get();
                    if ($this->week_day) {
                        foreach ($this->week_day as $index => $week_day) {
                            $timeSlotType = TimeSlotType::where('id', $week_day->time_slot_type_id)->first();
                            $this->slot_time = DB::table('time_slots')->where('time_from', '>=', $timeSlotType->time_from)->where('time_to', '<=', $timeSlotType->time_to)->get();
                            if ($index == 0){
                                foreach ($this->slot_time as $slot) {
                                    $startTime = Carbon::parse($slot->time_from);
                                    $finishTime = Carbon::parse($slot->time_to);
                                    $totalDuration = $finishTime->diffInSeconds($startTime);
                                    if (gmdate('i', $totalDuration) . " min" == $timeSlotType->slot_duration) {
                                        $this->collection[] = [$slot->id, $slot->time_from, $slot->time_to,''];

                                    }
                                }
                            }
                        }
                        $arrayCount = count($this->collection);
                        $duplicatedArray = array();
                        for ($i=0;$i<$arrayCount;$i++){
                            $duplicatedArray[$this->collection[$i][1]] = $this->collection[$i][1];
                        }
                        for($i=0; $i < count($duplicatedArray);$i++){
                            $this->data[] = $this->collection[$i];
                        }
                        $this->dt = Carbon::now();
                        $this->dis_data = [];
                        $this->disable_time = \App\Models\ObjectBooking::where([['date',$this->date],['object_id',$this->object_id],['is_cancelled',0]])->get();
                        if($this->disable_time){
                            foreach ($this->disable_time as $disable){
                                $disable_slot_time_data = \App\Models\TimeSlot::where('id',$disable->slot_id)->first();
                                foreach ($this->data as $index => $d){
                                    if ($d[1] == $disable_slot_time_data->time_from) {
                                        $this->dis_data[$index] = [$d[0],$disable_slot_time_data->time_from,$disable_slot_time_data->time_to,'dis'];
                                    }
                                }
                            }
                            $this->newData =[];
                            foreach ($this->data as $d){
                                if(!in_array($d,$this->dis_data)){
                                    $this->newData[] = $d;
                                }
                            }
                            $result = array_merge($this->newData,$this->dis_data);
                            sort($result);
                            for($val = 0;$val < count($result)-1;$val++) {
                                if($result[$val][0] == $result[$val+1][0]){
                                    $this->arrayFilter[]=$result[$val+1];
                                    $val++;
                                }else{
                                    $this->arrayFilter[]=$result[$val];
                                }
                            }
                            if($result[count($result)-1][3] !== 'dis'){
                                $this->arrayFilter[] = $result[count($result)-1];
                            }
                        }
                    }
                }
            }
        }
    }
    public function done(){
        if ($this->slotTime){
                $this->createFlag = true;
            }
    }

    public function OnlineDoctorData(){
        $this->doct = Doctor::where('has_zoom',1)->get();
    }
    public function OfflineDoctorData(){
        $this->doct = Doctor::all();
    }
    public function store(Request $request){
        $dt = strtotime($this->user_object_booking);
        $this->days = date("l", $dt);
        $this->date = Carbon::parse($this->user_object_booking)->format('Y-m-d');
        $this->dayNumber = Carbon::parse($this->date)->dayOfWeek;
        $weekDay = ObjectWeekDays::where([['time_slot_type_id',$this->objectTimeSlotType->time_slot_type_id],['week_day_number',$this->dayNumber]])->first();
        $slotID = TimeSlot::where([['object_week_days_id',$weekDay->id],['time_from',$this->slotTime]])->first();
        $validated = $this->validate();
        if($validated) {
            if($this->full_name && $this->phone) {
                $findUser = User::where('phone_number','+962'.$this->phone)->first();
                if($findUser){
                    Session::flash('existUser','The User is exist....please add another phone number');
                    return view('livewire.create-new-user-appointment');
                }else {
                    $user = new User();
                    $user->full_name = $this->full_name;
                    $user->phone_number = '+962'.$this->phone;
                    $user->save();
                    $user_booking = new UserBookings();
                    $user_booking->user_id = $user->id;
                    $user_booking->reservation_record_id = null;
                    $user_booking->from_dash = 1;
                    if ($user_booking->save()) {
                        $user_booking = UserBookings::find($user_booking->id);
                        $user_booking->reservation_record_id = $user_booking->id;
                        $user_booking->save();
                        $object_booking = new ObjectBooking();
                        $object_booking->reservation_record_id = $user_booking->reservation_record_id;
                        $object_booking->date = $this->user_object_booking;
                        $object_booking->object_id = $this->object_id;
                        $object_booking->slot_id = $slotID->id;
                        $object_booking->is_online = $this->is_online;
                        $object_booking->is_cancelled = 0;
                        $object_booking->save();
                        $date = Carbon::parse($this->user_object_booking)->format('Y-m-d');
                        $slotTime = TimeSlot::where("id", $slotID->id)->first();
                        if ($this->is_online == "1") {
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
                            $meeting->booking_id = $object_booking->id;
                            $meeting->zoom_url = $meetingData["join_url"];
                            $meeting->meeting_id = $meetingData["id"];
                            $meeting->save();
                        }
                            $timeFrom = strtotime($slotTime->time_from);
                            $timeTo = strtotime($slotTime->time_to);
                            $newFormatFrom = date('H:i', $timeFrom);
                            $newFormatTo = date('H:i', $timeTo);
                            $doctor = Doctor::where("object_id", $this->object_id)->first();
                            $userId = UserBookings::where("reservation_record_id", $user_booking->reservation_record_id)->first()->user_id;
                            if (!$userId) {
                                return redirect()->route('admin.date.index');
                            }
                            $user = User::where("id", $userId)->first();
                            $doctorNotificationLang = User::where("id", $doctor->user_id)->first()->notification_lang;
                            $userNotificationMsg = $this->getNotificationTextDetails("new_booking", ["name" => $doctor->full_name, "type" => $this->is_online, "date" => $this->date, "from" => $newFormatFrom, "to" => $newFormatTo]);
                            $doctorNotificationMsg = $this->getNotificationTextDetails("new_booking", ["name" => $user->full_name, "type" => $this->is_online, "date" => $this->date, "from" => $newFormatFrom, "to" => $newFormatTo]);
                            $doctorNotificationTitle = $doctorNotificationMsg['title'][$doctorNotificationLang];
                            $doctorNotificationBody = $doctorNotificationMsg['body'][$doctorNotificationLang];
                            $userNotificationTitle = $userNotificationMsg['title'][$user->notification_lang];
                            $userNotificationBody = $userNotificationMsg['body'][$user->notification_lang];
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

                            $this->notificationReminders($object_booking->id,$object_booking->date);


                    }
                }
            }
            Session::flash('success','Create New Appointment Successfully for '.$this->full_name);
            $this->dispatchBrowserEvent('close-modal');
            $this->reset(['full_name','phone','object_id','is_online','user_object_booking','slotTime']);
            return redirect()->route('admin.date.index');
        }
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
        foreach ($objectBookingCarbon as $Carbon){
            $booking_notification_times = new BookingNotificationTime();
            $booking_notification_times->booking_id = $id;
            $booking_notification_times->notification_date = $Carbon['notification_date'][0];
            $booking_notification_times->notification_time = $Carbon['notification_time'][0];
            $booking_notification_times->is_sent = 0;
            $booking_notification_times->save();
        }
    }
    public function render()
    {
        return view('livewire.create-new-user-appointment');
    }
}
