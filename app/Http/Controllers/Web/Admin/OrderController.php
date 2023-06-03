<?php

namespace App\Http\Controllers\Web\Admin;

use App\Models\PosOrders;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Traits\Helper;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isEmpty;

class OrderController extends Controller
{
    use Helper;


    public function index(){
        $data["orders"] = Order::all();
        return view("admin.orders.index",$data);
    }

    public function details(Request $request){
        $data["order"] = Order::findOrFail($request->id);
        return view("admin.orders.details",$data);
    }


    public function posOrders(){
        $data["orders"] = PosOrders::all();
        return view("admin.orders.posOrders",$data);
    }

    public function exportExcelFile(Request $request){
        $columns = [
            "order_number", "phone_number", "paymentMethod", "totalQty", "tax", "DropOffAddress", "Total_Amount",
            "custom" => [
                "name" => "Status",
                "values" =>
                    [
                        1 => "Pending",
                        2 => "Accept",
                        3 => "Prepare",
                        4 => "Ready",
                        5 => "Delivered",
                        6 => "Reject"
                    ]
            ],
        ];

        $headings = ["number", "phone number", "payment method",
            "total quantity", "tax", "address", "total amount", "status"];
        if(!isset($request->status) || $request->status == 0){
            $orders = Order::all();
        }else{
            $orders = Order::where("Status", $request->status)->get();
        }

        if($orders->isNotEmpty())
            return $this->downloadExcelFile($columns, $orders, $headings,"orders.xlsx");
        else
            return redirect()->back();

    }
}
