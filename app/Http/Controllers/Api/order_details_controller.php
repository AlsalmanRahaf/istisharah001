<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class order_details_controller extends Controller
{
    //

    public function getMyOrders(Request $request){

       
        if($request->type == "cashier"){

            $table="pos_orders";
            
        }elseif($request->type == "user"){
            
                   $table="orders";

        }else{
            return response()->json("error",404);
        }
        
         $query=DB::select("select $table.*, branches.store_name as branchSelected from $table,branches where $table.phone_number = '$request->phone_number' and $table.branchSelected=branches.id ORDER BY id DESC");

        return response($query);
    }
    
    
    
    public function getInvoice(){

                 $query = 'select pos_orders.*,branches.store_name as branchSelected,users.full_name,pos_orders.print_number  from pos_orders,`users`,branches WHERE pos_orders.phone_number = users.phone_number and pos_orders.branchSelected=branches.id ORDER BY id DESC';
                 $query=DB::select($query);
                 return response($query);

    }

    public function getMyOrderDetails(Request $request){


        $query=DB::select("SELECT * FROM `order_items` WHERE `order_id` = '$request->order_id'");

        return response($query);
    }


    public  function getOrderItemDetails(Request $request){

                 
                $request->validate(["order_id"=>"required", "order_by" =>"required"]);
                
                
                $orderID =$request ->order_id;
                $orderby=$request  ->order_by;
                
                if($orderby == "u" ||  $orderby == "c"){
                    
                    
                                    $getOrderItem=DB::select('SELECT JSON_ARRAYAGG(JSON_OBJECT(
                "quantity",order_items.quantity,
                "itemPrice",order_items.itemPrice,
                "totalPrice",order_items.totalPrice,
                "item_image",order_items.item_image,
                "item_name_en",order_items.item_name_en,
                "item_name_ar",order_items.item_name_ar,
                "instruction",(select orders.instruction from orders where orders.id=order_items.order_id limit 1),
                "DropOffAddress",(select orders.DropOffAddress from orders where orders.id=order_items.order_id limit 1),
                "sub_local",(select user_addresses.sub_local from user_addresses,orders where orders.phone_number=user_addresses.user_phone and orders.DropOffAddress=user_addresses.title and user_addresses.user_phone=orders.phone_number limit 1) ,
                "order_addons",
                (select JSON_ARRAYAGG(JSON_OBJECT( "AddOns_Category_name",order_addons.AddOns_Category_name,"AddOns_name_en",add_ons_options.name_en,"AddOns_name_en",add_ons_options.name_ar ,"AddOns_price",order_addons.AddOns_price )) from
                order_addons join add_ons_options on add_ons_options.id=order_addons.AddOns_id where order_addons.order_item_id=order_items.id  and  order_addons.OrderBy ="'.$orderby.'" )



                ))

                from order_items  where order_items.OrderBy ="'.$orderby.'" and  order_id='.$orderID );



                   return array_values(get_object_vars($getOrderItem[0]));

                    
                    
                }else{
            
                return response()->json(["Error"],404);        
                }
                


    }
}
