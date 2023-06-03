<?php

namespace App\Http\Controllers\Web\Admin;

use App\Models\TimeSlotType;
use App\Repositories\TimeSlotTypeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TimeSlotTypeController extends Controller
{
    protected TimeSlotTypeRepository $repository;

    public function __construct(TimeSlotTypeRepository $repository){
        $this->repository = $repository;
    }

    public function index(){
        $data['time_slot_type'] = TimeSlotType::all();
        return view('admin.time_slot_type.index',$data);
    }


    public function details(Request $request){
        $data['time_slot_type_details'] = TimeSlotType::find($request->id);
        return view('admin.time_slot_type.details',$data);
    }
}
