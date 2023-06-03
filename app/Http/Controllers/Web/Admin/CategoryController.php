<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\DangerMessage;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Rules\AlphaSpace;
use App\Rules\ArAlphaSpace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function rules($page, Request $request){
        $rules =  [
            "name_en" => ["required", new AlphaSpace(), "max:255"],
            "name_ar" => ["required",new ArAlphaSpace(), "max:255"],
        ];

        if ($page == "create"){
            $rules['category_photo'] = ["required", "file", "mimes:jpg,jpeg,png,bmp","max:512"];
        }elseif ($page == "edit" && $request->hasFile("category_photo")){
            $rules['category_photo'] = ["file", "mimes:jpg,jpeg,png,bmp","max:512"];
        }

        return $rules;
    }
    public function columns(){
        return [
            "name_en" => "english category name",
            "name_ar" => "arabic category name",
        ];
    }

    public function index(){
        $data['categories'] = Category::all();
        return view("admin.categories.index", $data);
    }

    public function create(){
        return view("admin.categories.create");
    }

    public function store(Request $request){
        $valid = Validator::make($request->all(), $this->rules("create", $request), [], $this->columns());
        if ($valid->fails())
            return redirect()->route("admin.categories.create")->withInput($request->all())->withErrors($valid->errors()->messages());

        $category = new Category();
        $category->name_en = $request->name_en;
        $category->name_ar = $request->name_ar;
        $category->save();
        $category->saveMedia($request->file("category_photo"));

        $message = (new SuccessMessage())->title(__('Created Successfully'))
            ->body(__("The Category Has Been Created Successfully"));
        Dialog::flashing($message);

        return redirect()->route("admin.categories.index");
    }

    public function edit(Request $request){
        $data['category'] = Category::find($request->id);
        return view("admin.categories.edit", $data);
    }

    public function update(Request $request){
        $valid = Validator::make($request->all(), $this->rules("edit", $request), [], $this->columns());
        if ($valid->fails())
            return redirect()->route("admin.categories.edit", $request->id)->withInput($request->all())->withErrors($valid->errors()->messages());

        $category = Category::find($request->id);
        $category->name_en = $request->name_en;
        $category->name_ar = $request->name_ar;
        $category->status = isset($request->category_status) ? 1 : 0;
        $category->save();

        if ($request->hasFile("category_photo")){
            if ($category->getFirstMediaFile())
                $category->removeMedia($category->getFirstMediaFile());
            $category->saveMedia($request->file("category_photo"));
        }

        $message = (new SuccessMessage())->title(__('Updated Successfully'))
            ->body(__("The Category Has Been Updated Successfully"));
        Dialog::flashing($message);

        return redirect()->route("admin.categories.index");
    }

    public function destroy(Request $request){
        $category = Category::find($request->id);
        if ($category->getFirstMediaFile())
            $category->removeAllFiles();
        $category->delete();
        $message = (new DangerMessage())->title(__('Deleted Successfully'))
            ->body(__("The Category Has Been Deleted Successfully"));
        Dialog::flashing($message);
        return redirect()->route("admin.categories.index");
    }
}
