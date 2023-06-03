<?php

namespace App\Http\Controllers\Web\Admin;



use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class FireWallController extends Controller
{
    public  function  index(Request $request){
        $data["route"] = $request->route;
        return  view("admin.auth.adminFirewall",$data);
    }

    public function check_admin(Request $request){
        $valid = Validator::make($request->all(), ["user_name"=>"required","password"=>"required","route"=>"required"]);
        if($valid->fails()){
            return redirect()->route("admin.admins.Firewall")->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $password = Auth::user()->password;
        $username = Auth::user()->username;

        if (Hash::check($request->password, $password) && $username == $request->user_name) {
            return redirect()->route($request->route)->withInput($request->all())->withErrors($valid->errors()->messages());
        }else{
            return redirect()->route("admin.admins.Firewall",["route"=>$request->route])->withInput($request->all())->withErrors(["ERROR"]);
        }
    }

}
