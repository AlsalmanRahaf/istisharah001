<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class app_settings_controller extends Controller
{
    //
    public function getAppSettings(){


        $query=DB::select("SELECT * FROM app_settings where 1");

        return response($query);


    }

    public function updateIsAccepting(Request $request){


        $query=DB::select("UPDATE `app_settings` SET `accepting_order`= $request->isAccept");

        return response($query);


    }


    public function getIsAcceptOrder(){


        $query=DB::select("SELECT `accepting_order` FROM `app_settings`");

        return response($query);


    }
}
