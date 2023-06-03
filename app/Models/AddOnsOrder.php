<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddOnsOrder extends Model
{
    protected $table="order_addons";

    protected $fillable=['order_item_id','AddOns_Category_id','AddOns_Category_name','AddOns_id','AddOns_name','AddOns_price','OrderBy'];





}
