<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class vendor_controller extends Controller
{
    //
    public function gelAllItems(){
        $items = Item::all();
        $data = [];
        foreach ($items as $index => $item) {
            $data[$index]['id'] = $item->id;
            $data[$index]['item_name_en'] = $item->name_en;
            $data[$index]['item_name_ar'] = $item->name_ar;
            $data[$index]['item_description_en'] = $item->description_en;
            $data[$index]['item_description_ar'] = $item->description_ar;
            $data[$index]['item_price'] = $item->price;
            $data[$index]['tax'] = $item->tax;
            $data[$index]['quantity'] = $item->quantity;
            $data[$index]['item_status'] = $item->status;
            $data[$index]['category_id'] = $item->category_id;
            $data[$index]['item_image'] = $item->getFirstMediaFile("main_photo") ? $item->getFirstMediaFile("main_photo")->url : Null;

        }
        return response($data);
    }

    public function gelDisableItems(){

        $items = Item::where("status", 2)->get();
        $data = [];
        foreach ($items as $index => $item) {
            $data[$index]['id'] = $item->id;
            $data[$index]['item_name_en'] = $item->name_en;
            $data[$index]['item_name_ar'] = $item->name_ar;
            $data[$index]['item_description_en'] = $item->description_en;
            $data[$index]['item_description_ar'] = $item->description_ar;
            $data[$index]['item_price'] = $item->price;
            $data[$index]['tax'] = $item->tax;
            $data[$index]['quantity'] = $item->quantity;
            $data[$index]['item_status'] = $item->status;
            $data[$index]['category_id'] = $item->category_id;
            $data[$index]['item_image'] = $item->getFirstMediaFile("main_photo") ? $item->getFirstMediaFile("main_photo")->url : Null;

        }

        return response($data);
    }

    public function gelEnableItems(){

        $items = Item::where("status", 1)->get();
        $data = [];
        foreach ($items as $index => $item) {
            $data[$index]['id'] = $item->id;
            $data[$index]['item_name_en'] = $item->name_en;
            $data[$index]['item_name_ar'] = $item->name_ar;
            $data[$index]['item_description_en'] = $item->description_en;
            $data[$index]['item_description_ar'] = $item->description_ar;
            $data[$index]['item_price'] = $item->price;
            $data[$index]['tax'] = $item->tax;
            $data[$index]['quantity'] = $item->quantity;
            $data[$index]['item_status'] = $item->status;
            $data[$index]['category_id'] = $item->category_id;
            $data[$index]['item_image'] = $item->getFirstMediaFile("main_photo") ? $item->getFirstMediaFile("main_photo")->url : Null;

        }

        return response($data);
    }

    public function updateStatusToInActive(Request $request){

        $query=DB::select("UPDATE `items` SET `status`= 2 WHERE `id` = $request->itemId");

        return response($query);
    }


    public function updateStatusToActive(Request $request){

        $query=DB::select("UPDATE `items` SET `status`= 1 WHERE `id` = $request->itemId");

        return response($query);
    }

    public function getEstimation(){

        $query=DB::select("SELECT `delivery_estimation` FROM `app_settings`");

        return response($query);
    }

    public function updateEstimationTime(Request $request){

        $query=DB::select("UPDATE `app_settings` SET `delivery_estimation`= $request->newTime");

        return response($query);
    }
    
    public function getPendingOrder(){

        $query=DB::select("SELECT orders.*, users.full_name FROM `orders`, `users` WHERE orders.phone_number = users.phone_number and orders.Status = 1");

        return response($query);
    }
    
    public function getAcceptedOrder(){

        $query=DB::select("SELECT orders.*, users.full_name FROM `orders`, `users` WHERE orders.phone_number = users.phone_number and orders.Status = 2");

        return response($query);
    }
    
    public function getPreparedOrder(){

        $query=DB::select("SELECT orders.*, users.full_name FROM `orders`, `users` WHERE orders.phone_number = users.phone_number and orders.Status = 3");

        return response($query);
    }
    
    public function getReadyOrder(){


        $query=DB::select("SELECT orders.*,branches.store_name as branchSelected, users.full_name FROM `orders`, `users`,branches WHERE orders.phone_number = users.phone_number and orders.branchSelected=branches.id  and orders.Status = 4");

        return response($query);
    }
    
    public function getDeliveredOrder(){

        $query=DB::select("SELECT orders.*, users.full_name FROM `orders`, `users` WHERE orders.phone_number = users.phone_number and orders.Status = 5");

        return response($query);
    }
    
    public function getCanceledOrder(){

        $query=DB::select("SELECT orders.*, users.full_name FROM `orders`, `users` WHERE orders.phone_number = users.phone_number and orders.Status = 6");

        return response($query);
    }
    
    
    public function Is_Auto_Print(Request $request){
        
                $isAuto=DB::select("select *  from vendor_branches where vendor_id = $request->vendorID and auto_print=1");
                
               if($isAuto != []){
                   
                    return response()->json(["status"=>1],200);
               }else{
                   return response()->json(["status"=>0],200);
               }
                   
               
              

    }
    
    
    public function Edit_auto_print(Request $request){
        
            try {
                 DB::select("update vendor_branches set auto_print=$request->newStatus where vendor_id = $request->vendorID");
                 return response()->json(["status"=>"success"],200);
            } catch(Exception $e) {
                 return response()->json(["error"=>"update unsuccess"],404);
            }
            


        
 
    }
    
}
