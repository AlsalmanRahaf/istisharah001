<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\DangerMessage;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Http\Controllers\Controller;
use App\Models\Specialization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SpecializationController extends Controller
{
    public function rules(){
        return [
            "name_en"=>['required', "max:255"],
            "name_ar"=>['required', "max:255"]
        ];
    }
    public function index()
    {
        $data['specializations'] = Specialization::all();
        for($i=0; $i<count($data['specializations']); $i++){
            $imageData = $data["specializations"][$i]->getFirstMediaFile("Specializations");
            $data["specializations"][$i]["image"] = $imageData != null  ? $imageData["path"]."/".$imageData["filename"] : null;
        }
        return view("admin.specialization.index", $data);
    }


    public function create()
    {
        return view("admin.specialization.create");
    }


    public function store(Request $request)
    {
        $valid = Validator::make($request->all(), $this->rules());
        if($valid->fails()){
            return redirect()->route("admin.specialization.create")->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        DB::beginTransaction();
        try {
        $specialization = new Specialization();
        $specialization->name_en = $request->name_en;
        $specialization->name_ar = $request->name_ar;
        $specialization->description_en = $request->description_en;
        $specialization->description_ar = $request->description_ar;
        if($specialization->save()){
            if($request->hasFile("specialization_image")){
                $specialization->saveMedia($request->file("specialization_image"),'Specializations');
            }
        }
            DB::commit();
        $message = (new SuccessMessage())->title("Create Successfully")
            ->body("The Specialization Has Been Create Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.specialization.index");
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function edit(Request $request)
    {
        $data['specialization'] = Specialization::find($request->id);
        return view("admin.specialization.edit", $data);
    }


    public function update(Request $request)
    {
        $valid = Validator::make($request->all(), $this->rules());
        if($valid->fails()){
            return redirect()->route("admin.specialization.edit", $request->id)->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        DB::beginTransaction();
        try {
        $specialization = Specialization::findOrFail($request->id);
        $specialization->name_en = $request->name_en;
        $specialization->name_ar = $request->name_ar;
        $specialization->description_en = $request->description_en;
        $specialization->description_ar = $request->description_ar;
        $specialization->save();
        if($request->hasFile("specialization_image")){
            $specialization->removeAllFiles();
            $specialization->saveMedia($request->file("specialization_image"),'Specializations');
        }
            DB::commit();
        $message = (new SuccessMessage())->title("Updated Successfully")
            ->body("The Specialization Has Been Updated Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.specialization.index");
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function cancel(){
        return redirect()->route("admin.specialization.index");
    }
    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {
        $specialization = Specialization::find($request->id);
        if($specialization->delete()){
            $specialization->removeAllFiles();
        }
            DB::commit();
        $message = (new DangerMessage())->title("Deleted Successfully")
            ->body("The Specialization Has Been Deleted Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.specialization.index");
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
