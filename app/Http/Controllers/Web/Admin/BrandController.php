<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\DangerMessage;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    public function rules($page, Request $request){
        $rules = [
            "name_en"           => ['required'],
            "name_ar"           => ['required'],
            "description_en"    => ['required'],
            "description_ar"    => ['required'],
            "category_id"       => ['required'],
        ];

        if ($page == "create"){
            $rules["image"] = ["required", "file", "mimes:jpg,jpeg,png,bmp","max:512"];
        }elseif ($page == "edit" && $request->hasFile("image")){
            $rules["image"] = ["file", "mimes:jpg,jpeg,png,bmp", "max:512"];
        }

        return $rules;
    }

    public function index(){
        $data['brands'] = Brand::all();
        return view("admin.brands.index", $data);
    }

    public function create(Request $request){
        $data['categories'] = Category::all();
        return view("admin.brands.create", $data);
    }

    public function store(Request $request){
        $valid = Validator::make($request->all(), $this->rules("create", $request));
        if ($valid->fails())
            return redirect()->route("admin.brands.create")->withInput($request->all())->withErrors($valid->errors()->messages());

        $brand  = new Brand();
        $brand->name_en = $request->name_en;
        $brand->name_ar = $request->name_ar;
        $brand->description_en = $request->description_en;
        $brand->description_ar = $request->description_ar;
        $brand->category_id = $request->category_id;
        $brand->save();
        $brand->saveMedia($request->file("image"));

        $message = (new SuccessMessage())->title(__('Created Successfully'))
            ->body(__("The Brand Has Been Created Successfully"));
        Dialog::flashing($message);

        return redirect()->route("admin.brands.index");
    }

    public function edit(Request $request){
        $data['brand'] = Brand::find($request->id);
        $data['categories'] = Category::all();
        return view("admin.brands.edit", $data);
    }

    public function update(Request $request){
        $valid = Validator::make($request->all(), $this->rules("edit", $request));
        if ($valid->fails())
            return redirect()->route("admin.brands.edit", $request->id)->withInput($request->all())->withErrors($valid->errors()->messages());

        $brand = Brand::find($request->id);
        $brand->name_en = $request->name_en;
        $brand->name_ar = $request->name_ar;
        $brand->description_en = $request->description_en;
        $brand->description_ar = $request->description_ar;
        $brand->save();

        if ($request->hasFile("image")){
            if ($brand->getFirstMediaFile())
                $brand->removeMedia($brand->getFirstMediaFile());
            $brand->saveMedia($request->file("image"));
        }

        $message = (new SuccessMessage())->title(__('Updated Successfully'))
            ->body(__("The Brand Has Been Updated Successfully"));
        Dialog::flashing($message);

        return redirect()->route("admin.brands.index");
    }

    public function destroy(Request $request){
        $brand = Brand::find($request->id);
        if ($brand->getFirstMediaFile())
            $brand->removeAllFiles();
        $brand->delete();

        $message = (new DangerMessage())->title(__('Deleted Successfully'))
            ->body(__("The Brand Has Been Deleted Successfully"));
        Dialog::flashing($message);

        return redirect()->route("admin.brands.index");
    }
}
