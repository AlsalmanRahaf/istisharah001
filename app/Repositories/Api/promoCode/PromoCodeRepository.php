<?php

namespace App\Repositories\Api\promoCode;

use App\Models\PromoCode;
use App\Models\UsesPromoCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PromoCodeRepository
{


    public function createPromoCode(){

       $userid=Auth::user()->id;

       if(!PromoCode::where("user_id",$userid)->exists()){

           $PromoCode=new PromoCode;
           $PromoCode->user_id=$userid;
           $PromoCode->Promo_code=Str::random(5);
           $PromoCode->points=0;
           if($PromoCode->save())
               return $PromoCode->Promo_code;
       }
       return 0;
    }

    public function UsesPromoCode($request){

        $uses_user_id=Auth::user()->id;
        $promo_code_text=$request->promo_code;

        $promo_code=PromoCode::where("Promo_code",$promo_code_text)->where("user_id","!=",$uses_user_id)->first();
        if($promo_code){
            if(!UsesPromoCode::where([["user_id",$uses_user_id],["promo_code_id",$promo_code->id]])->exists()){
                $UsesPromoCode=new UsesPromoCode();
                $UsesPromoCode->user_id=$uses_user_id;
                $UsesPromoCode->promo_code_id=$promo_code->id;
                if($UsesPromoCode->save()){
                    $promo_code->points=$promo_code->points+100;
                    $promo_code->save();
                    return 1;
                }
            }
            return  "Error when use promoCode already in used ";
        }
        return "you can't use promo code because you create it";
    }




}


