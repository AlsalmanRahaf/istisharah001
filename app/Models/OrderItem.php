<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
     protected $table="order_items";

    protected $fillable=['order_id','item_id','quantity','itemPrice','totalPrice','category_id','item_image','item_name_en','item_name_ar','OrderBy'];


    public function order(){
        return $this->belongsTo(Order::class, "order_id", "id");
    }

    public function addOns(){
        return $this->hasMany(AddOnsOrder::class, "order_item_id", "id");

    }

}
