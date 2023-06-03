<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\DangerMessage;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\CategoryBranch;
use App\Rules\MapCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BranchController extends Controller
{
    public function rules($page, Request $request){
        $rules  = [
            "phone_number"  => ["required"],
            "category_id"   => ["required"],
            "latitude"      => ["required"],
            "longitude"      => ["required"],
        ];

        if ($page == "create"){
            $rules['img'] = ["required", "file", "mimes:jpg,jpeg,png,bmp","max:512"];
            $rules['store_name'] = ["required", "unique:branches,store_name"];
        }elseif ($page == "edit" && $request->hasFile("img")){
            $rules['img'] = ["file", "mimes:jpg,jpeg,png,bmp","max:512"];
        }

        if ($page == "edit"){
            $rules['store_name'] = ["required"];
        }

        return $rules;
    }

    public function index(){
        $data['branches'] = Branch::all();
        return view("admin.branches.index", $data);
    }

    public function create(){
        $data["categories"] = CategoryBranch::all();
        return view("admin.branches.create",$data);
    }

    public function store(Request $request){
        $valid = Validator::make($request->all(), $this->rules("create", $request));
        if ($valid->fails())
            return redirect()->route("admin.branches.create")->withInput($request->all())->withErrors($valid->errors()->messages());

        $branch = new Branch();
        $branch->store_name = $request->store_name;
        $branch->phone_number = $request->phone_number;
        $branch->latitude = $request->latitude;
        $branch->longitude = $request->longitude;
        $branch->address = $request->address;
        $branch->category_id = $request->category_id;
        $branch->save();
        $branch->saveMedia($request->file("img"));

        $message = (new SuccessMessage())->title(__('Created Successfully'))
            ->body(__("The Branch Has Been Created Successfully"));
        Dialog::flashing($message);

        return redirect()->route("admin.branches.index");
    }

    public function edit(Request $request){
        $data['branch'] = Branch::find($request->id);
        $data["categories"] = CategoryBranch::all();
        return view("admin.branches.edit", $data);
    }

    public function update(Request $request){
        $valid = Validator::make($request->all(), $this->rules("edit", $request));
        if ($valid->fails())
            return redirect()->route("admin.branches.edit", $request->id)->withInput($request->all())->withErrors($valid->errors()->messages());

        $branch = Branch::find($request->id);
        $branch->store_name = $request->store_name;
        $branch->phone_number = $request->phone_number;
        $branch->latitude = $request->latitude;
        $branch->longitude = $request->longitude;
        $branch->address = $request->address;
        $branch->category_id = $request->category_id;
        $branch->save();
        if ($request->hasFile("img")){
            if ($branch->getFirstMediaFile())
                $branch->removeMedia($branch->getFirstMediaFile());
            $branch->saveMedia($request->file("img"));
        }

        $message = (new SuccessMessage())->title(__('Updated Successfully'))
            ->body(__("The Branch Has Been Updated Successfully"));
        Dialog::flashing($message);

        return redirect()->route("admin.branches.index");
    }

    public function destroy(Request $request){
        $branch = Branch::find($request->id);
        if ($branch->getFirstMediaFile())
            $branch->removeAllFiles();
        $branch->delete();
        $message = (new DangerMessage())->title(__('Deleted Successfully'))
            ->body(__("The Branch Has Been Deleted Successfully"));
        Dialog::flashing($message);

        return redirect()->route("admin.branches.index");
    }
}
