<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\DangerMessage;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Http\Controllers\Controller;
use App\Models\Consultant;
use App\Models\consultation;
use App\Models\Doctor;
use App\Models\Specialization;
use App\Models\TimeSlotType;
use App\Models\User;
use App\Traits\IntegrationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ConsultantController extends Controller
{
    use IntegrationTrait;
    public  function rules(){
        return [
            "full_name" => ["required", "max:255"],
            "phone_number" => ["required", "max:255"],
            "email" => ["required","email"],
            "specialization" => ["required"],
            "time_slot_type" => ["required"],
            "payment" => ["required","array","between:1,2"],
            "consultant_description" =>["required"],
            "doctor_image"=>["required"]
        ];
    }
    public  function updateRules(){
        return [
            "full_name" => ["required", "max:255"],
            "phone_number" => ["required", "max:255"],
            "email" => ["required","email"],
            "payment" => ["required","array","between:1,2"],
            "consultant_description" =>["required"],
            "doctor_image"=>["required"]
        ];
    }

    public function index()
    {
        $data['consultants'] = Consultant::all();
        for($i=0; $i<count($data['consultants']); $i++){
            $data['consultants'][$i]["specialization_en"] = Specialization::where("id", $data['consultants'][$i]["specialization_id"])->first()->name_en;
            $data['consultants'][$i]["specialization_ar"] = Specialization::where("id", $data['consultants'][$i]["specialization_id"])->first()->name_ar;
            $imageData = $data["consultants"][$i]->getFirstMediaFile("Doctors");
            $data["consultants"][$i]["image"] = $imageData != null  ? $imageData["path"]."/".$imageData["filename"] : null;
        }

        return view("admin.consultant.index", $data);
    }

    public function create()
    {
        $data["specializations"] = Specialization::all();
        $data["timeSlotTypes"] = TimeSlotType::all();
        $data["users"] = User::all();
        return view("admin.consultant.create", $data);
    }

    public function store(Request $request)
    {
        $valid = Validator::make($request->all(), $this->rules());

        if($valid->fails()){
            return redirect()->route("admin.consultant.create")->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        DB::beginTransaction();
        try {
            $paymentsMethodsArr = [];
            if( isset($request->cash))
                $paymentsMethodsArr[] = "cash";
            if( isset($request->online))
                $paymentsMethodsArr[] = "online";

            $consultant = new Consultant();
            $consultant->full_name = $request->full_name;
            $consultant->phone_number = $request->phone_number;
            $consultant->email = $request->email;
            $consultant->specialization_id = $request->specialization;
            $consultant->user_id = $request->user_id ?? null;
            $consultant->has_zoom = isset($request->has_zoom) ? 1 : 0;
            $consultant->payment_methods = json_encode($paymentsMethodsArr);
            $consultant->description = $request->consultant_description ;

            if( $consultant->save()){
//                $image       = $request->file('doctor_image');
//                $image_resize = Image::make($image->getRealPath());
//                $image_resize->resize(300, 300);
//                $image_resize->save(public_path('uploads/Doctors/' .$filename));
                if($request->hasFile("doctor_image")){
                    $consultant->saveMedia($request->doctor_image,'Doctors');
                }
//                dd($doctor->getFirstMediaFile('Doctors'));

                $response = $this->addNewObject($request);

                $user = User::where("phone_number", $request->phone_number);
                $checkPhoneNumber=$user->exists();
                $user = $user->first();
//                dd($doctor->user_id);
                if(is_null($consultant->user_id)){
                    if(!$checkPhoneNumber){
                        $user = new User();
                        $user->full_name = $request->full_name;
                        $user->phone_number = $request->phone_number;
                        $user->save();

                    }
                }



                if($response->getData()->status){
                    $consultant = Consultant::findOrFail( $consultant->id);
                    $consultant->object_id = $response->getData()->objectId;
                    if(!$checkPhoneNumber) {
                        $consultant->user_id = $user->id;
                    }
                    $consultant->save();
                }else{
                    $message = (new DangerMessage())->title("Create Failed")
                        ->body("Failed In Create Object");
                    Dialog::flashing($message);
                    return redirect()->route("admin.consultant.index");
                }
            }
            DB::commit();
            $message = (new SuccessMessage())->title("Create Successfully")
                ->body("The consultant Has Been Create Successfully");
            Dialog::flashing($message);
            return redirect()->route("admin.consultant.index");
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function edit(Request $request)
    {
        $objectDetails = $this->getObjectInfo($request);
        $timeSlotTypes = $this->getAllTimeSlotTypes();
        $data['consultant'] = Consultant::find($request->id);
        if (!is_null($data['consultant']->payment_methods)){
            $paymentMethodsArr = json_decode($data['consultant']->payment_methods);
            if (in_array("cash", $paymentMethodsArr))
                $data['consultant']["cash"] = 1;
            else
                $data['consultant']["cash"] = 0;
            if (in_array("online", $paymentMethodsArr))
                $data['consultant']["online"] = 1;
            else
                $data['consultant']["online"] = 0;
        }
        $data['objectDetails'] = $objectDetails->getData()->data;
        $data["specializations"] = Specialization::all();
        $data["timeSlotTypes"] = $timeSlotTypes->getData()->data;
        $data["users"] = User::all();
        return view("admin.consultant.edit", $data);
    }

    public function update(Request $request)
    {
        $valid = Validator::make($request->all(), $this->updateRules());
        if($valid->fails()){
            return redirect()->route("admin.consultant.edit", $request->id)->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        DB::beginTransaction();
        try {
            $paymentsMethodsArr = [];
            if(in_array("cash", $request->payment))
                $paymentsMethodsArr[] = "cash";
            if(in_array("online", $request->payment))
                $paymentsMethodsArr[] = "online";
            $consultant = Consultant::findOrFail($request->id);
            $consultant->full_name = $request->full_name;
            $consultant->phone_number = $request->phone_number;
            $consultant->email = $request->email;
            $consultant->specialization_id = $request->specialization;
            $consultant->user_id = $request->user_id ?? null;
            $consultant->has_zoom = isset($request->has_zoom) ? 1 : 0;
            $consultant->payment_methods = json_encode($paymentsMethodsArr);
            $consultant->description = $request->consultant_description;
            $consultant->save();
            if($request->hasFile("doctor_image")){

                $consultant->removeAllFiles();
                $consultant->saveMedia($request->file("doctor_image"),'Doctors');
            }
            $this->updateObject($request);
            DB::commit();
            $message = (new SuccessMessage())->title("Updated Successfully")
                ->body("The Doctor Has Been Updated Successfully");
            Dialog::flashing($message);
            return redirect()->route("admin.consultant.index");
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function cancel(){
        return redirect()->route("admin.consultant.index");
    }
    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {
            $consultant = Consultant::find($request->id);
            $objectId =  $consultant->object_id;
            if( $consultant->delete()){
                $consultant->removeAllFiles();
                $this->deleteObject($objectId);
            }
            DB::commit();
            $message = (new DangerMessage())->title("Deleted Successfully")
                ->body("The consultant Has Been Deleted Successfully");
            Dialog::flashing($message);
            return redirect()->route("admin.consultant.index");
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}


