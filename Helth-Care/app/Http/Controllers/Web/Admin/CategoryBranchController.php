<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Rules\AlphaSpace;
use App\Rules\ArAlphaSpace;

class CategoryBranchController extends Controller
{
    public function rules(){
        return [
            "name_en" => ["required", new AlphaSpace(), "max:255"],
            "name_ar" => ["required",new ArAlphaSpace(), "max:255"],
            "category_photo" => ["required", "file", "mimes:jpg,jpeg,png,bmp","max:512"],
        ];
    }
    public function fields_names(){
        return [
            "name_en" => "english category name",
            "name_ar" => "arabic category name",
        ];
    }
}
