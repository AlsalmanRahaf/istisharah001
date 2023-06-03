<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosOrders extends Model
{
    //
    
        protected $fillable= ["order_number", "phone_number", "Status", "paymentMethod",
        "totalQty", "tax", "Total_Amount", "DropOffAddress", "pickUpAddress", "instruction",
        "token", "branchSelected", "delivered_time","print_number"
    ];

    public function items(){
        return $this->hasMany(OrderItem::class, "order_id", "id");
    }
}
