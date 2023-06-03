<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\DangerMessage;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Http\Controllers\Controller;
use App\Models\BookingNotificationReminder;
use App\Models\BookingNotificationTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookingNotificationReminderController extends Controller
{
    public function rules(){
        return [
            "duration_number" => ['required', 'numeric'],
            "duration_type" => ['required', "max:255"]
        ];
    }
    public function index()
    {
        $data['durations'] = BookingNotificationReminder::all();
        return view("admin.notification-reminder.index", $data);
    }


    public function create()
    {
        return view("admin.notification-reminder.create");
    }


    public function store(Request $request)
    {
        $valid = Validator::make($request->all(), $this->rules());
        if($valid->fails()){
            return redirect()->route("admin.notification-reminder.create")->withInput($request->all())->withErrors($valid->errors()->messages());
        }

        $duration = new BookingNotificationReminder();
        $duration->duration_number = $request->duration_number;
        $duration->duration_type = $request->duration_type;
        $duration->save();
        $message = (new SuccessMessage())->title("Create Successfully")
            ->body("The Booking Notification Reminder Has Been Create Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.notification-reminder.index");
    }

    public function edit(Request $request)
    {
        $data['duration'] = BookingNotificationReminder::find($request->id);
        return view("admin.notification-reminder.edit", $data);
    }


    public function update(Request $request)
    {
        $valid = Validator::make($request->all(), $this->rules());
        if($valid->fails()){
            return redirect()->route("admin.notification-reminder.edit", $request->id)->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $duration = BookingNotificationReminder::findOrFail($request->id);
        $duration->duration_number = $request->duration_number;
        $duration->duration_type = $request->duration_type;
        $duration->save();
        $message = (new SuccessMessage())->title("Updated Successfully")
            ->body("The Booking Notification Reminder Has Been Updated Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.notification-reminder.index");
    }

    public function cancel(){
        return redirect()->route("admin.notification-reminder.index");
    }
    public function destroy(Request $request)
    {
        $duration = BookingNotificationReminder::find($request->id);
        $duration->delete();
        $message = (new DangerMessage())->title("Deleted Successfully")
            ->body("The Booking Notification Reminder Has Been Deleted Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.notification-reminder.index");
    }
}
