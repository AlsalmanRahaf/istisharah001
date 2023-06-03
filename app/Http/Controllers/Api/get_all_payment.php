<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class get_all_payment extends Controller
{
    //
    public function getAllPayment (){

        $query=DB::select("SELECT * FROM payment_methods");
        return response($query);
    }
}
