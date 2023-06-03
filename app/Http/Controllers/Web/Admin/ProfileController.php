<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{


    protected function rules(){
        return [
            "full_name" => ["required"],
            "username" => ["required"],
            "email" => ["required"],
            "oldpassword" => ["required"],
            "password" => ["required|confirmed"]
        ];
    }
    public function index()
    {
        $Admin=Admin::find(Auth::id());
        return view("admin.profile.index",$Admin);
    }

    public function Update(Request $request){

        $valid = Validator::make($request->all(), $this->rules());
        if($valid->fails()){
            return redirect()->route("admin.profile.index")->withInput($request->all())->withErrors($valid->errors()->messages());
        }
            $id=$request->id;
            $data = array();
            $data['full_name'] = $request->full_name;
            $data['username'] = $request->username;
            $data['email'] = $request->email;

            DB::table('admins')->where('id',$id)->update($data);

        $Admin=Admin::find(Auth::id());
        return redirect()->route("admin.profile.index");
    }



    public function ChanagePassword(Request $request){

//             $validateData = $request->validate([
//                 'oldpassword' => 'required',
//                 'password' => 'required|confirmed'
//             ]);
//
//             $hashedPassword = Auth::user()->password;
//             if(Hash::check($request->oldpassword,$hashedPassword)){
//                 $user = User::find(Auth::id());
//                 $user->password = Hash::make($request->password);
//                 $user->save();
//                 Auth::logout();
//                 return redirect()->route('login')->with('success','Password Is Chanage Successfuly');
//
//             }else{
//                 return redirect()->back()->with('error','Current Password IS Invalid');
//             }

         }


}
