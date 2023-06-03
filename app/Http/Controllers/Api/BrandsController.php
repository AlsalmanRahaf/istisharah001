<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use App\Models\Item;
use Illuminate\Http\Request;

class BrandsController extends Controller
{
    public function index(Request $request){
        $brands = Brand::all();
        $data = [];
        foreach ($brands as $brand) {
            $data[] = [
                "id" => $brand->id,
                "name" => $brand->name,
                "description" => $brand->getDescriptionAttribute(),
                "image" => $brand->getFirstMediaFile() ? $brand->getFirstMediaFile()->url : null,
            ];
        }
        return $data;
    }

    public  function BrandCategory(Request $request){
        $brand = Brand::find($request->brand_id);
        $data = [];
        $data['id'] = $brand->category->id;
        $data['category_name_en'] = $brand->category->name_en;
        $data['category_name_ar'] = $brand->category->name_ar;
        $data['category_image_url'] = $brand->category->getFirstMediaFile() ? $brand->category->getFirstMediaFile()->url : Null;
        return response()->json( [$data],200);

    }



    public  function get_all_items_brands(Request $request){

        $items = Item::join("categories","categories.id","=","items.category_id")
            ->join("brands","brands.category_id", "=", "categories.id")
            ->where("brands.id", $request->brand_id)->get("items.*");
        $data = [];
        foreach ($items as $index => $item) {
            $data[$index]["id"] = $item->id;
            $data[$index]["category_id"] = $item->category_id ;
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
        return response()->json( $data,200);

    }
}
