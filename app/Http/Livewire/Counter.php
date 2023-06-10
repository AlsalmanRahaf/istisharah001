<?php

namespace App\Http\Livewire;
use App\Models\Consultant;
use App\Models\ObjectBooking;
use App\Models\ObjectDetails;
use App\Models\ObjectWeekDays;
use App\Models\TimeSlot;
use App\Models\TimeSlotType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Http\Request;

class Counter extends Component
{
    public $isSetClicked = false;
    public $user_object_booking,
        $days,
        $object_id,
        $slot_time,
        $slotTime,
        $user_id,
        $doct,
        $data,
        $Consultants,
        $is_online,
        $Consultant,
        $user_Consultant,
        $res_id,
        $time,
        $week_day,
        $newConsultant,
        $patient,
        $oldConsultant,
        $object_week_days,
        $flag = false,
        $onlineFlag = false,
        $onlineConsultant,
        $createFlag = false,
        $collection,
        $date,
        $objectTimeSlotType,
        $dayNumber,
        $user,
        $dis_data,
        $user_slot_time,
        $newData,
        $disable_time,
        $arrayFilter=[],
        $dt;
    public function mount(Request $request){
        $this->user_object_booking1= ObjectBooking::where('reservation_record_id',$this->res_id)->first();
        $this->user_object_booking= ObjectBooking::where('reservation_record_id',$this->res_id)->first()->date ?? $this->user_object_booking;
        $this->user_slot_time = TimeSlot::where('id',$this->user_object_booking1->slot_id)->first();
        $this->user_Consultant = Consultant::where('object_id', $this->user_object_booking1->object_id)->first();
        if ($this->user_object_booking1->is_online == 1){
            $this->OnlineConsultantData();
        }else{
            $this->OfflineConsultantData();
        }
        $this->object_week_days = ObjectWeekDays::where('id', $this->user_slot_time->object_week_days_id)->first();
        $this->object_id = $this->user_Consultant->object_id ?? $this->object_id ;
        $this->handleTime($request);

    }
    public function onlineOrNot(){
        $this->flag = true;
        $this->createFlag = true;
        if($this->is_online == 1){
            $this->OnlineConsultantData();
        }else{
            $this->OfflineConsultantData();
        }
    }

    public function OnlineConsultantData(){
        $this->doct = Consultant::where('has_zoom',1)->where('user_id','!=',$this->user_id)->get();
    }
    public function OfflineConsultantData(){
        $this->doct = Consultant::where('user_id','!=',$this->user_id)->get();
    }
    public function SetClicked(Request $request)
    {
        $this->onlineOrNot();
        $this->handleTime($request);
        $this->isSetClicked == false ? $this->isSetClicked = true : $this->isSetClicked = false;
    }

    public function handleTime(Request $request){
        $this->onlineConsultant = Consultant::where('object_id',$this->object_id)->first();
        if ($this->onlineConsultant->has_zoom == 1){
            $this->onlineFlag = true;
        }else{
            $this->onlineFlag = false;
        }
        if (($this->user_Consultant->object_id && $this->object_id) || ($this->object_id && $this->user_object_booking)){
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
            $this->dt = strtotime($request->user_object_booking);
            $this->days = date("l", $this->dt);
            $this->date = Carbon::parse($this->user_object_booking)->format('Y-m-d');
            $this->dayNumber = Carbon::parse($this->date)->dayOfWeek;
            $this->objectTimeSlotType = ObjectDetails::where("id", $this->object_id)->first();
            $checkDate = ObjectWeekDays::where([["time_slot_type_id", $this->objectTimeSlotType->time_slot_type_id], ["week_day_number", $this->dayNumber]])->exists();
            if ($checkDate) {
                $this->Consultants = Consultant::where('object_id', '=', $this->object_id)->first();
                $this->time = ObjectDetails::where('description', $this->Consultants->full_name)->first();
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
    public function ConsultantData(){
        $this->doct = Consultant::all();
    }
    public function userData(){
        $this->users = User::all();
    }
    public function render()
    {
        return view('livewire.counter');
    }
}
