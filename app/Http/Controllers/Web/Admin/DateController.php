<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\CancelledBooking;
use App\Models\Date;
use App\Models\Doctor;
use App\Models\ObjectBooking;
use App\Models\ObjectWeekDays;
use App\Models\OnlineBooking;
use App\Models\TimeSlot;
use App\Models\User;
use App\Models\UserBookings;
use App\Repositories\AppointmentRepository;
use App\Traits\Firebase;
use App\Traits\IntegrationTrait;
use App\Traits\Notifications as NotificationsTrait;
use App\Traits\ZoomMeetingTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Js;

class DateController extends Controller
{
    use IntegrationTrait;
    use Firebase;
    use ZoomMeetingTrait;
    use NotificationsTrait;
    const MEETING_TYPE_INSTANT = 1;
    const MEETING_TYPE_SCHEDULE = 2;
    const MEETING_TYPE_RECURRING = 3;
    const MEETING_TYPE_FIXED_RECURRING_FIXED = 8;

    private $repository;
    public function __construct(AppointmentRepository $repository){
        $this->repository = $repository;
    }
    public function index(Request $request)
    {
        $this->repository->index($request);
        $data['doctors'] = Doctor::all();
        if ($request->object_id == null || $request->reset === "reset"){
            $data['users_booking'] = UserBookings::join('object_bookings','user_bookings.reservation_record_id','=','object_bookings.reservation_record_id')
            ->join('doctors','object_bookings.object_id','=','doctors.object_id')
            ->get(['user_bookings.*','object_bookings.*','doctors.full_name','doctors.phone_number','doctors.email','doctors.specialization_id','doctors.has_zoom','doctors.object_id']);
            $data['selected_doctor'] = null;
        } else{
            $data['users_booking'] = UserBookings::join('object_bookings','user_bookings.reservation_record_id','=','object_bookings.reservation_record_id')
                ->join('doctors','object_bookings.object_id','=','doctors.object_id')
                ->where('object_bookings.object_id',$request->object_id)
                ->get(['user_bookings.*','object_bookings.*','doctors.full_name','doctors.phone_number','doctors.email','doctors.specialization_id','doctors.has_zoom','doctors.object_id']);
            $data['selected_doctor'] = Doctor::where('object_id',$request->object_id)->first();
        }
        return view('admin.date.index',$data);
    }

    public function create(){
        $data['doctors']= Doctor::all();
        $data['users'] = User::all();
        return view('admin.date.create',$data);
    }

    public function details(Request $request){
        if ($request->id  && $request->reservation_record_id && $request->object_id) {
            $data['doctor'] = Doctor::where('object_id',$request->object_id)->first();
            $data['users_booking'] = UserBookings::where('user_bookings.reservation_record_id', $request->reservation_record_id)
                ->join('object_bookings', 'user_bookings.reservation_record_id', '=', 'object_bookings.reservation_record_id')
                ->join('doctors', 'object_bookings.object_id', '=', 'doctors.object_id')
                ->first(['user_bookings.*','object_bookings.*','doctors.full_name','doctors.has_zoom','doctors.email','doctors.phone_number']);
            $data['user_id'] =  UserBookings::where('user_bookings.reservation_record_id', $request->reservation_record_id)->first();
            if ($data['user_id'])$data['user'] = User::where('id',$data['user_id']->user_id)->first();
            return view('admin.date.details',$data);
        }else {
            $data['doctor'] = Doctor::where('object_id',$request->object_id)->first();
            $data['users_booking'] = UserBookings::where('user_bookings.reservation_record_id', $request->reservation_record_id)
                ->join('object_bookings', 'user_bookings.reservation_record_id', '=', 'object_bookings.reservation_record_id')
                ->join('doctors', 'object_bookings.object_id', '=', 'doctors.object_id')
                ->first(['user_bookings.*','object_bookings.*','doctors.full_name','doctors.has_zoom','doctors.email','doctors.phone_number']);
            $data['user_data'] =  UserBookings::where('user_bookings.reservation_record_id', $request->reservation_record_id)->first();
            $data['user'] = null;
            return view('admin.date.details',$data);
        }
    }

    public function edit(Request $request)
    {
        $data['reservation_record_id'] = $request->reservation_record_id;
        $data['user_object_booking'] = ObjectBooking::where('reservation_record_id',$request->reservation_record_id)->first();
        $data['user_booking'] = UserBookings::where('reservation_record_id',$request->reservation_record_id)->first()->user->full_name;
        $data['user_slot_time'] = TimeSlot::where('id', $data['user_object_booking']->slot_id)->first();
        $data['user_doctor'] = Doctor::where('object_id', $data['user_object_booking']->object_id)->first();
        $data['object_week_days'] = ObjectWeekDays::where('id', $data['user_slot_time']->object_week_days_id)->first();
        $data['user_id'] = $request->user_id;
        $data['doctors'] = Doctor::all();
        $data['slot_time'] = TimeSlot::all();
        return view('admin.date.edit',$data);
    }


    public function update(Request $request)
    {
        $this->repository->update($request);
        return redirect()->route('admin.date.index');

    }
}
