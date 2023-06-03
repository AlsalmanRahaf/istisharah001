<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AddOns;
use App\Models\Item;
use App\Models\ItemColor;
use App\Models\ItemSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ItemSerialNumber;
use App\Models\ItemGallery;



class get_item_details_controller extends Controller
{
    //


    // protected function checkSerial($serialno){

    //     return ItemSerialNumber::where('serial_no',$serialno)->exists();
    // }

      protected function checkItemExists($item_id){

        return Item::where('id',$item_id)->exists();
    }


    public function checkItemSizes($item_id){
        return ItemSize::where('item_id',$item_id)->exists();
    }

    public function checkItemColor($item_id){

        return ItemColor::where('item_id',$item_id)->exists();

    }


    //item images



    public function getItemImages(Request $request){
        $item = Item::find($request->item_id);
        if($item){

            if($item->getFirstMediaFile("more_photo")){
                $data = [];
                foreach ($item->getFirstMediaFile("more_photo") as $file) {
                    $data[]["image_url"] = $file->url;
                }
                return $data;
            }else{
                return response()->json([" this item  not have  gallery images  "],404);
            }


        }else{
           return response()->json([" this item id not found "],404);
        }


        $query=DB::select("SELECT items.* FROM `items`");

        return response($query);


    }



    // Item Sizes

        public function getItemSizes(Request $request){
          $item = Item::find($request->item_id);

          if($item){
              if($this->checkItemSizes($item->id)){
                  return ItemSize::where('item_id',$item->id)->get(["id","size"]);
                }else{
                  return response()->json([" this item  not have  Sizes   "],404);
                }
            }else{
               return response()->json([" this item id not found "],404);
            }

            $query=DB::select("SELECT items.* FROM `items`");

        return response($query);


    }


        //item Colors

    public function getItemColor(Request $request){
        $item = Item::find($request->item_id);

        if($item){
            if($this->checkItemColor($item->id)) {
                $colors = ItemColor::where('item_id', $item->id)->get(["id", DB::raw("REPLACE(color,'\r\n','') as color")]);
                $data = [];
                foreach ($colors as $index => $color) {
                    $data[$index]['id'] = $color->id;
                    $data[$index]['color'] = $color->color;
                    $data[$index]['url_image'] = $color->getFirstMediaFile()->url;
                }
                return $data;
            }else
                return response()->json([" this item  not have  color   "],404);

        }else{
            return response()->json([" this item id not found "],404);
        }

        $query=DB::select("SELECT items.* FROM `items`");

        return response($query);


    }



    //item Details


    public function getItemDetails(Request $request){
        $item = Item::find($request->itemId);
        $data['id'] = $item->id;
        $data['item_name_en'] = $item->name_en;
        $data['item_name_ar'] = $item->name_ar;
        $data['item_description_en'] = $item->description_en;
        $data['item_description_ar'] = $item->description_ar;
        $data['item_price'] = $item->price;
        $data['tax'] = $item->tax;
        $data['quantity'] = $item->quantity;
        $data['item_status'] = $item->status;
        $data['category_id'] = $item->category_id;
        $data['item_image'] = $item->getFirstMediaFile("main_photo") ? $item->getFirstMediaFile("main_photo")->url : Null;
        return response([$data]);
    }

    public function getItemAddOnsCategory(Request $request){
        $item_id = $request->itemId;
        $query=DB::select("select
                                JSON_ARRAYAGG(JSON_OBJECT('Option',
                                (select JSON_ARRAYAGG(JSON_OBJECT('id',add_ons_options.id,'en',add_ons_options.name_en,'ar',add_ons_options.name_ar,'price',add_ons_options.price))
                                from
                                add_ons_options where  add_ons.id=add_ons_options.add_on_id and  add_ons.`item_id` = $item_id)
                                ,'id',add_ons.id,'en',add_ons.name_en,'ar',add_ons.name_ar,'which_choice',add_ons.type_input))
                                from
                                add_ons where add_ons.item_id= $item_id
                                 ");

         return array_values(get_object_vars($query[0]));

    }

    public function getListOfAddOns(Request $request){

        $query=DB::select("SELECT * FROM add_ons_options WHERE add_on_id = $request->addOnsCatId");

        return response($query);
    }
}
