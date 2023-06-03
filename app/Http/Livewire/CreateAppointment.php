<?php

namespace App\Http\Livewire;

use App\Models\TimeSlot;
use Illuminate\Http\Request;
use Livewire\Component;

class CreateAppointment extends Component
{
    public $object_booking;
    public $slot_time;
    public $days;

    public function handleTime(Request $request){
        $dt = strtotime($this->object_booking);
        $this->days = date("l", $dt);
        $this->slot_time = TimeSlot::all();

    }

    public function render()
    {
        return view('livewire.create-appointment');
    }
}
