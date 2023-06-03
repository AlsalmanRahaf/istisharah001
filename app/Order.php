<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
     protected $table="orders";

    protected $fillable=['order_number','paymentMethod','totalQty','Total_Amount','DropOffAddress','phone_number','instruction','priceWithDelivery','deliveryFee','branchSelected', 'delivered_time','token'];
}
