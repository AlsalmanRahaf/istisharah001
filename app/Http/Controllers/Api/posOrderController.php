<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\PosOrders;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Traits\Firebase;
use App\Traits\Notifications;
use App\Models\User;
use App\Models\OrderItem;
use App\Models\AddOnsOrder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class posOrderController extends Controller
{
    use Notifications,Firebase;

    public function store(Request $request)
    {

        try{

            $orderNumber=DB::select("select max(order_number) as MaxOrderNumber from pos_orders limit 1");
            $MaxOrderNumber=$orderNumber[0]->MaxOrderNumber;


            if(empty($MaxOrderNumber)){
                $MaxOrderNumber=0;
            }


            $TotlaPrice=$request -> cart['totalItemPrice'];
            $TotalQuentety=$request ->cart['totalQty'];
            $addressSelected=$request ->cart['addressSelected'];
            $paymentSelected=$request ->cart['paymentSelected'];
            $phoneNumber=$request ->cart['phoneNumber'];
            $instruction=$request ->cart['instruction'];

            $priceWithDelivery=$request ->cart['priceWithDelivery'];
            $deliveryFee=$request ->cart['deliveryFee'];
            $branchSelected=$request ->cart['branchSelected'];
            $token=$request ->cart['token'];
            $order_time = $request->cart["orderTime"];



            $createOrder=PosOrders::create([
                'order_number'=>$MaxOrderNumber+1,
                'Total_Amount'=>$TotlaPrice,
                'paymentMethod'=>$paymentSelected,
                'DropOffAddress'=>$addressSelected,
                'totalQty'=>$TotalQuentety,
                'phone_number'=>$phoneNumber,
                'instruction'=>$instruction,
                'priceWithDelivery'=>$priceWithDelivery,
                'deliveryFee'  =>$deliveryFee,
                'branchSelected'=>$branchSelected,
                'delivered_time' => $order_time,
                'token'=>$token
            ]);



            if($createOrder)  {


                $orderID= $createOrder['id'];


                $items=$request->cart['items'];


                foreach( $items as $item){

                    $ItemID =$item['item']['id'];
                    $QuentetyItem=$item['quantity'];
                    $ItemPrice =$item['item']['item_price'];
                    $TotalPriceItem=$item['totalPrice'];
                    $Category_Id =$item['item']['category_id'];
                    $ItemImage =$item['item']['item_image'];
                    $Item_Name_En =$item['item']['item_name_en'];
                    $Item_Name_Ar =$item['item']['item_name_ar'];




                    $createOrderItem=OrderItem::create([
                        'order_id'=>$orderID,
                        'item_id'=>$ItemID,
                        'quantity'=>$QuentetyItem,
                        'itemPrice'=>$ItemPrice,
                        'totalPrice'=>$TotalPriceItem,
                        'category_id'=>$Category_Id,
                        'item_image'=>$ItemImage,
                        'item_name_en'=>$Item_Name_En,
                        'item_name_ar'=>$Item_Name_Ar,
                        'OrderBy'=>"c"
                    ]);



                    if($createOrderItem){

                        $orderItemID=$createOrderItem['id'];
                        $addOnsByCategory=$item['addOnsByCategory'];

                        foreach($addOnsByCategory as $category){

                            $catgoryid=$category['id'];
                            $catgoryname=$category['name'];
                            $addons=$category['addons'];

                            foreach($addons as $addon){
                                $addonid= $addon['id'];
                                $addonname= $addon['name'];
                                $addoneprice= $addon['price'];

                                $craeteAddonse=AddOnsOrder::create([
                                    'order_item_id'=>$orderItemID,
                                    'AddOns_Category_id'=>$catgoryid,
                                    'AddOns_Category_name'=>$catgoryname,
                                    'AddOns_id'=>$addonid,
                                    'AddOns_name'=>$addonname,
                                    'AddOns_price'=>$addoneprice,
                                    'OrderBy'=>"c"

                                ]);

                            }
                        }

                    }
                }
            }

            $user= User::where('phone_number',$createOrder['phone_number'])->first(['full_name','id']);

            if($user){
                $createOrder['full_name']=$user ->full_name;
                $createOrder['userid']=$user->id;
            }

            $notification_texts = $this->getNotificationTextDetails("pending_order", ["order_number" => $createOrder['id']]);
            $notification = new \App\Models\Notifications();
            $notification->title = $notification_texts["title"]["en"];
            $notification->body = $notification_texts["body"]["en"];
            $notification->type = 1;
            $notification->status = 1;
            $notification->user_id = $user->id;
            $notification->is_sent = 1;
            $notification->save();
            $this->sendNotificationNew($notification_texts, [$createOrder->token]);

            return response()->json(['reply'=>$createOrder]);


        }catch(\Illuminate\Database\QueryException $ex){
            $error=$ex->getMessage();
            return response($ex);
        }

    }
    //***** custom function




    public function getMyOrderDetails(Request $request){


        $query=DB::select("SELECT * FROM `order_items` WHERE `order_id` = '$request->order_id'");

        return response($query);
    }


    public  function getOrderItemDetails(Request $request){

        $orderID =$request ->order_id;
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
                order_addons join add_ons_options on add_ons_options.id=order_addons.AddOns_id where order_addons.order_item_id=order_items.id )



                ))

                from order_items  where order_id='.$orderID);



        return array_values(get_object_vars($getOrderItem[0]));


    }

   public function getInvoiceFooter(Request $request){

       if(Branch::where("id", $request->branch_id)->exists()){
           $data=Branch::where("id", $request->branch_id)->get("InvoiceFooterMessage");
          return response(["FooterMessage"=>$data[0]["InvoiceFooterMessage"]]);
           
       }else{
        
        return response()->json(["Error"=>"this branch id not found"],404);   
        
       }

   }


   public function getPrintNumber(Request $request){
       
       $request->validate(["order_id"=>"required","type"=>"required"]);
       
       $order_id=$request->order_id;
       $type=$request->type;
       
       
       if($type == "user"){
                
                if(Order::where('id',$order_id)->exists()){
                    
                     $data=Order::where('id',$order_id)->get(["print_number"]);
                     return response()->json($data,200);    
    
                    
                }else{
                     return response()->json(["error"=>"this order id not found in user orders"],404);

                }
           
       }elseif($type == "cashier"){          
           
           
            if(PosOrders::where('id',$order_id)->exists()){
                
                     $data=PosOrders::where('id',$order_id)->get(["print_number"]);
                     return response()->json($data,200);    
            
            }else{
                                 return response()->json(["error"=>"this order id not found in cashier invoice"],404);

            }
          
       }else{
           return response()->json(["error"=>"this type of order not found"],404);
       }
      

   }

   public function EditPrintOrder(Request $request){
       
            
            $request->validate(["order_id"=>"required","type"=>"required"]);
            
            $order_id=$request->order_id;
            $type=$request->type;

       
              if($type == "user"){
                
                if(Order::where('id',$order_id)->exists()){
                    
                     $data=Order::where('id',$order_id)->update(["print_number"=>DB::raw('print_number+1')]);
                     
                     if($data){
                     return response()->json(["success update Order"],200);    
                     }else{
                         
                         return response()->json("unsuccess Edit user order",404);
                     }
                         
    
                    
                }else{
                     return response()->json(["error"=>"this order id not found in user orders"],404);

                }
           
       }elseif($type == "cashier"){          
           
           
            if(PosOrders::where('id',$order_id)->exists()){
                
                     $data=PosOrders::where('id',$order_id)->update(["print_number"=>DB::raw('print_number+1')]);
                     
                          if($data){
                     return response()->json(["success update invoice"],200);    
                     }else{
                         
                         return response()->json("unsuccess Edit invoice order",404);
                     }
                     
       
            
            }else{
                                 return response()->json(["error"=>"this order id not found in cashier invoice"],404);

            }
          
       }else{
           return response()->json(["error"=>"this type of order not found"],404);
       }
       
   }






}
