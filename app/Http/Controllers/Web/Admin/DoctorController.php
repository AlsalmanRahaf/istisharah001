<?php
//
//namespace App\Http\Controllers\Web\Admin;
//use Intervention\Image\ImageManagerStatic as Image;
//use App\Helpers\Dialog\Web\Dialog;
//use App\Helpers\Dialog\Web\Types\DangerMessage;
//use App\Helpers\Dialog\Web\Types\SuccessMessage;
//use App\Http\Controllers\Controller;
//use App\Models\Doctor;
//use App\Models\Specialization;
//use App\Models\TimeSlotType;
//use App\Models\User;
//use App\Rules\AlphaSpace;
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Validator;
//use App\Traits\IntegrationTrait;
//
//class DoctorController extends Controller
//{
//    use IntegrationTrait;
//    public  function rules(){
//        return [
//            "full_name" => ["required", "max:255"],
//            "phone_number" => ["required", "max:255"],
//            "email" => ["required","email"],
//            "specialization" => ["required"],
//            "time_slot_type" => ["required"],
//            "payment" => ["required","array","between:1,2"],
//            "doctor_description" =>["required"],
//            "doctor_image"=>["required"]
//        ];
//    }
//    public  function updateRules(){
//        return [
//            "full_name" => ["required", "max:255"],
//            "phone_number" => ["required", "max:255"],
//            "email" => ["required","email"],
//            "payment" => ["required","array","between:1,2"],
//            "doctor_description" =>["required"],
//            "doctor_image"=>["required"]
//        ];
//    }
//
//    public function index()
//    {
//        $data['doctors'] = Doctor::all();
//        for($i=0; $i<count($data['doctors']); $i++){
//            $data['doctors'][$i]["specialization_en"] = Specialization::where("id", $data['doctors'][$i]["specialization_id"])->first()->name_en;
//            $data['doctors'][$i]["specialization_ar"] = Specialization::where("id", $data['doctors'][$i]["specialization_id"])->first()->name_ar;
//            $imageData = $data["doctors"][$i]->getFirstMediaFile("Doctors");
//            $data["doctors"][$i]["image"] = $imageData != null  ? $imageData["path"]."/".$imageData["filename"] : null;
//        }
//
//        return view("admin.doctor.index", $data);
//    }
//
//    public function create()
//    {
//        $data["specializations"] = Specialization::all();
//        $data["timeSlotTypes"] = TimeSlotType::all();
//        $data["users"] = User::all();
//        return view("admin.doctor.create", $data);
//    }
//
//    public function store(Request $request)
//    {
//        $valid = Validator::make($request->all(), $this->rules());
//
//        if($valid->fails()){
//            return redirect()->route("admin.doctor.create")->withInput($request->all())->withErrors($valid->errors()->messages());
//        }
//        DB::beginTransaction();
//        try {
//            $paymentsMethodsArr = [];
//            if( isset($request->cash))
//                $paymentsMethodsArr[] = "cash";
//            if( isset($request->online))
//                $paymentsMethodsArr[] = "online";
//
//            $doctor = new Doctor();
//            $doctor->full_name = $request->full_name;
//            $doctor->phone_number = $request->phone_number;
//            $doctor->email = $request->email;
//            $doctor->specialization_id = $request->specialization;
//            $doctor->user_id = $request->user_id ?? null;
//            $doctor->has_zoom = isset($request->has_zoom) ? 1 : 0;
//            $doctor->payment_methods = json_encode($paymentsMethodsArr);
//            $doctor->description = $request->doctor_description ;
//
//            if($doctor->save()){
////                $image       = $request->file('doctor_image');
////                $image_resize = Image::make($image->getRealPath());
////                $image_resize->resize(300, 300);
////                $image_resize->save(public_path('uploads/Doctors/' .$filename));
//                if($request->hasFile("doctor_image")){
//                    $doctor->saveMedia($request->doctor_image,'Doctors');
//                }
////                dd($doctor->getFirstMediaFile('Doctors'));
//
//                $response = $this->addNewObject($request);
//
//                $user = User::where("phone_number", $request->phone_number);
//                $checkPhoneNumber=$user->exists();
//                $user = $user->first();
////                dd($doctor->user_id);
//                if(is_null($doctor->user_id)){
//                    if(!$checkPhoneNumber){
//                        $user = new User();
//                        $user->full_name = $request->full_name;
//                        $user->phone_number = $request->phone_number;
//                        $user->save();
//
//                    }
//                }
//
//
//
//                if($response->getData()->status){
//                    $doctor = Doctor::findOrFail($doctor->id);
//                    $doctor->object_id = $response->getData()->objectId;
//                    if(!$checkPhoneNumber) {
//                        $doctor->user_id = $user->id;
//                    }
//                    $doctor->save();
//                }else{
//                    $message = (new DangerMessage())->title("Create Failed")
//                        ->body("Failed In Create Object");
//                    Dialog::flashing($message);
//                    return redirect()->route("admin.doctor.index");
//                }
//            }
//            DB::commit();
//            $message = (new SuccessMessage())->title("Create Successfully")
//                ->body("The Doctor Has Been Create Successfully");
//            Dialog::flashing($message);
//            return redirect()->route("admin.doctor.index");
//        } catch (\Exception $e) {
//            DB::rollback();
//            throw $e;
//        }
//    }
//
//    public function edit(Request $request)
//    {
//        $objectDetails = $this->getObjectInfo($request);
//        $timeSlotTypes = $this->getAllTimeSlotTypes();
//        $data['doctor'] = Doctor::find($request->id);
//        if (!is_null($data['doctor']->payment_methods)){
//            $paymentMethodsArr = json_decode($data['doctor']->payment_methods);
//            if (in_array("cash", $paymentMethodsArr))
//                $data['doctor']["cash"] = 1;
//            else
//                $data['doctor']["cash"] = 0;
//            if (in_array("online", $paymentMethodsArr))
//                $data['doctor']["online"] = 1;
//            else
//                $data['doctor']["online"] = 0;
//        }
//        $data['objectDetails'] = $objectDetails->getData()->data;
//        $data["specializations"] = Specialization::all();
//        $data["timeSlotTypes"] = $timeSlotTypes->getData()->data;
//        $data["users"] = User::all();
//        return view("admin.doctor.edit", $data);
//    }
//
//    public function update(Request $request)
//    {
//        $valid = Validator::make($request->all(), $this->updateRules());
//        if($valid->fails()){
//            return redirect()->route("admin.doctor.edit", $request->id)->withInput($request->all())->withErrors($valid->errors()->messages());
//        }
//        DB::beginTransaction();
//        try {
//            $paymentsMethodsArr = [];
//            if(in_array("cash", $request->payment))
//                $paymentsMethodsArr[] = "cash";
//            if(in_array("online", $request->payment))
//                $paymentsMethodsArr[] = "online";
//            $doctor = Doctor::findOrFail($request->id);
//            $doctor->full_name = $request->full_name;
//            $doctor->phone_number = $request->phone_number;
//            $doctor->email = $request->email;
//            $doctor->specialization_id = $request->specialization;
//            $doctor->user_id = $request->user_id ?? null;
//            $doctor->has_zoom = isset($request->has_zoom) ? 1 : 0;
//            $doctor->payment_methods = json_encode($paymentsMethodsArr);
//            $doctor->description = $request->doctor_description;
//            $doctor->save();
//            if($request->hasFile("doctor_image")){
//
//               $doctor->removeAllFiles();
//                $doctor->saveMedia($request->file("doctor_image"),'Doctors');
//            }
//            $this->updateObject($request);
//            DB::commit();
//            $message = (new SuccessMessage())->title("Updated Successfully")
//                ->body("The Doctor Has Been Updated Successfully");
//            Dialog::flashing($message);
//            return redirect()->route("admin.doctor.index");
//        } catch (\Exception $e) {
//            DB::rollback();
//            throw $e;
//        }
//    }
//
//    public function cancel(){
//        return redirect()->route("admin.doctor.index");
//    }
//    public function destroy(Request $request)
//    {
//        DB::beginTransaction();
//        try {
//            $doctor = Doctor::find($request->id);
//            $objectId = $doctor->object_id;
//            if($doctor->delete()){
//                $doctor->removeAllFiles();
//                $this->deleteObject($objectId);
//            }
//            DB::commit();
//            $message = (new DangerMessage())->title("Deleted Successfully")
//                ->body("The Doctor Has Been Deleted Successfully");
//            Dialog::flashing($message);
//            return redirect()->route("admin.doctor.index");
//        } catch (\Exception $e) {
//            DB::rollback();
//            throw $e;
//        }
//    }
//}
//
//
////
////
////}