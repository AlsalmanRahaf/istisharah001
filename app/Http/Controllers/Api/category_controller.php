<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class category_controller extends Controller
{
    //

    public function getAllCategory($lang_code){

        $categories = Category::all();
        $data = [];


        foreach ($categories as $index => $category) {
            $data[$index]['id'] = $category->id;
            if (App::getLocale() == "en")
                $data[$index]['category_name_en'] = $category->name;
            else
                $data[$index]['category_name_ar'] = $category->name;
        }


        return response($data);

    }

    public function getAllItemFromCategory(Request $request){

        $items = Item::where("category_id", $request->categoryId)->get();
        $data = [];
        foreach ($items as $index => $item) {
            $data[$index]["id"] = $item->id;
            $data[$index]["category_id"] = $item->category_id;
            $data[$index]["item_name_en"] = $item->name_en;
            $data[$index]["item_name_ar"] = $item->name_ar;
            $data[$index]["item_price"] = $item->price;
            $data[$index]["tax"] = $item->tax;
            $data[$index]["quantity"] = $item->quantity;
            $data[$index]["item_description_en"] = $item->description_en;
            $data[$index]["item_description_ar"] = $item->description_ar;
            $data[$index]["item_status"] = $item->status;
            $data[$index]["item_image"] = $item->getFirstMediaFile("main_photo") ? $item->getFirstMediaFile("main_photo")->url : Null;
        }

        return response($data);

    }

    public function getCategoryByBranch(Request $request){
        $lang = App::getLocale();
        $categories = Category::join("items", "items.category_id", "=", "categories.id")
            ->join("item_branches",
                [["item_branches.item_id", "=", "items.id"],["item_branches.branch_id", "=", DB::raw($request->branch_id)]])
            ->groupBy("items.category_id")->select( "categories.id","categories.name_{$lang} as category_name")->get();
        if($categories) {
            $data = [];
            foreach ($categories as $index => $category) {
                $data[$index]['id'] = $category->id;
                $data[$index]['category_name'] = $category->category_name;
                $data[$index]['category_image_url'] = $category->getFirstMediaFile()->url;
            }
            return $data;
        }else
            return false;
    }
}
