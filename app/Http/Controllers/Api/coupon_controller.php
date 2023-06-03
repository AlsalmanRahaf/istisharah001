<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class coupon_controller extends Controller
{
    //
    public function getCouponByUser(Request $request){
        $sql = "SELECT  coupon.id as couponId, coupon.coupon_text,coupon.expiry_date, (coupon.uses_number - coupon_used.number_used) as 'already_used' , coupon.uses_number as 'avaliable_used',
        coupon.coupon_type, coupon.coupon_value, coupon_used.id, coupon_used.user_phone , coupon_used.number_used
        from coupon INNER JOIN coupon_used on coupon.id = coupon_used.coupon_id and coupon_used.user_phone = '" . $request->phone_number . "'";
        $query=DB::select($sql);
        return response($query);
    }
    
    public function useCoupon(Request $request){
        if( !isset($request->coupon_id) || !isset($request->phone_number)){
                return response()->json(["error"=>"missing"],404);
        }
        $sql = "SELECT (case when NOW() < coupon.expiry_date then 'not_expir' ELSE 'expir' end) as `expiry` , coupon_used.* FROM `coupon_used` 
        INNER JOIN coupon ON coupon.id = coupon_used.coupon_id 
        WHERE coupon_used.coupon_id = '" . $request->coupon_id . "' AND coupon_used.user_phone = '" . $request->phone_number . "'";
        $coupon=DB::select($sql);
        $coupon = $coupon[0];

        if(!empty($coupon)){
            if($coupon->expiry == "expir"){
                    return response()->json(["status" => false,"error"=>["en" => "Coupon is Expiry", "ar" => "الكوبون منتهي الصلاحية"],"status" => 2],404);
            }else if($coupon->number_used < 1){
                    return response()->json(["status" => false,"error"=>["en" => "Coupon is not valid", "ar" => "الكوبون غير صالح"],"status" => 2],404);
            }else{
                $sql = "UPDATE `coupon_used` SET coupon_used.number_used = coupon_used.number_used - 1 WHERE coupon_used.id = '" . $coupon->id . "'";
               DB::update($sql);
               return response()->json(["status" => true],200);
            }      
        }else{
            return response()->json(["status" => false,"error"=>"not found"],404);
        }
      
        

    }
    
    
    public function checkValidCoupon(Request $request){
        
        
                    $data = $request->validate([
            "phone_number"     => ["required"],
            "coupon_code"   => ["required"],
         

            
            ]);
           $phone_number= $request->phone_number;
           $coupon_titel= $request->coupon_code;
        
        // $query=DB::select("SELECT (case WHEN count(`coupon_used`.`coupon_id`) > coupon.uses_number then 'Out_Of_Used' else 'in_used' END) as status_used_number,(case when coupon.coupon_text='$coupon_titel' then 'match' ELSE 'not_match' end) as `status_match`
        // ,(case when NOW() < coupon.expiry_date then 'not_expir' ELSE 'expir' end) as `expiry`
        // ,coupon.id  FROM `coupon`,`coupon_used` where `coupon`.`id` = `coupon_used`.coupon_id and coupon_used.user_phone =$phone_number  ");
        
        // $sql = "SELECT coupon.id , (case WHEN count(`coupon_used`.`coupon_id`) >= coupon.uses_number then 'Out_Of_Used' else 'in_used' END) as 'status_used_number', 
        // (case when NOW() < coupon.expiry_date then 'not_expir' ELSE 'expir' end) as `expiry` 
        // FROM coupon LEFT JOIN coupon_used ON coupon_used.user_phone='" . $request->phone_number . "' WHERE coupon.coupon_text='" . $request->coupon_code . "'";
        
        $sql = "
        SELECT 
            (SELECT
             (case when count(*) > 0 then true ELSE false end)
             FROM coupon 
             INNER JOIN coupon_used ON coupon.id = coupon_used.coupon_id 
             WHERE coupon_used.user_phone = '" . $request->phone_number ."' AND coupon.coupon_text = '" . $request->coupon_code ."') as 'exist',
             (case when NOW() < coupon.expiry_date then 'not_expir' ELSE 'expir' end) as `expiry`,
             coupon.uses_number as 'used_number', id
            FROM `coupon` WHERE coupon.coupon_text = '" . $request->coupon_code ."'";
        
        $query=DB::select($sql);
        if(empty($query) || $query[0]->exist > 0 ||  $query[0]->expiry == "expir"){
            
            if(empty($query[0]->id)){
                return response()->json(["error"=>["en" => "Coupon not match", "ar" => "الكوبون غير متطابق"], "status" => 4],404);

            }
            else if($query[0]->exist > 0){
                return response()->json(["error"=>["en" => "Coupon already exists", "ar" => "انت مستخدم هذا الكوبون بالفعل"], "status" => 3],404);
            }else{
                $expir=$query[0]->expiry ;
                return response()->json(["error"=>["en" => "Coupon is Expiry", "ar" => "الكوبون منتهي الصلاحية"],"status" => 2],404);

            }

        }else{
        $coupon_id=$query[0]->id;
        
        DB::table("coupon_used")->insert(["coupon_id"=>$coupon_id,"user_phone"=>$phone_number, "number_used" => $query[0]->used_number]);
        
        return response()->json(["reply"=>"Add Coupon  success ","status" => 1]);    
        }
        
        
    }
    
    
}
