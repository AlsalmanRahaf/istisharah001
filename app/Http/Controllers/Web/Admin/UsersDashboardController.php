<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\UsersDashboard;
use App\Rules\AlphaSpace;
use App\Rules\Password;
use App\Rules\HashMatching;
use App\Traits\Helper;
use App\User;
use App\Models\Role;

use Illuminate\Support\Facades\File;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersDashboardController extends Controller
{
    use Helper;


    public function index(){
        if(!hasPermissions("admin-control"))
            abort("401");
        $data["users"] = Admin::where('deleted_at',null)->get();

        return view("admin.dashboard_users.index", $data);
    }

    public  function rules(){
        return [
            "full_name"     => ["required",new AlphaSpace(), "max:255"],
            "username"      => ["required", "max:255", "alpha_num", "unique:admins"],
            "email"         => ["required","email", "unique:admins"],
            "password"      => [ "required", new Password(), "confirmed"],
            "profile_photo" => [],
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!hasPermissions("admin-control"))
            abort("401");
        $data["roles"] = Role::all();
        return view("admin.dashboard_users.create",$data);
    }

    public function edit(Request $request)
    {
        if(!hasPermissions("admin-control"))
            abort("401");
        $user_id=$request->id;
        $data["admin"] = Admin::find($user_id);
        $data["roles"] = Role::all();
        return view("admin.dashboard_users.edit",$data);
    }

    public function show(Request $request)
    {
        $Admin["Admin_info"]=Admin::find($request->id);
        return view("admin.ManagerAdmin.show",$Admin);
    }

    public function store(Request $request )
    {

        $rules = $this->rules();
        if(!empty($request->file("profile_photo")))
            $rules["profile_photo"] = ["file", "mimes:jpg,jpeg,png,bmp","max:512"];
        $request->validate($rules);
        $user = new Admin();
        $user->full_name = $request->full_name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->role_id = $request->role;
        $user->token = $user->createToken('API Token')->accessToken;
        if(!empty($request->file("profile_photo"))){
            $photo_name = $this->upload($request->file("profile_photo"),$user->directory_path);
            $user->profile_photo = $photo_name;
        }
        $user->save();
        $this->setPageMessage("The Admin Has Been Created Successfully", 1);
        return redirect()->route("admin.admins.index");
    }


    public function update($lang,Request $request)
    {
//        $rules = $this->rules();
//        if(!empty($request->file("profile_photo")))
//        $rules["profile_photo"] = ["file", "mimes:jpg,jpeg,png,bmp","max:512"];
//        $rules["username"] = ["required", "max:255", "alpha_num"];
//        $rules["email"]=["required","email"];
//        $request->validate($rules);
        $id=$request->id;
        $user =Admin::find($id);
        $user->full_name = $request->full_name ?? $user->full_name;
        $user->username = $request->username ?? $user->username;
        $user->email = $request->email ?? $user->email;
        $user->password = $request->password ?? $user->password;
        if(!$user->is_super_admin){
            $user->role_id = $request->role_id ?? $user->role_id;
        }

        $user->token = $user->createToken('API Token')->accessToken;
        if(!empty($request->file("profile_photo"))){
            $photo_name = $this->upload($request->file("profile_photo"),$user->directory_path);
            $user->profile_photo = $photo_name;
        }
        $user->save();
        $this->setPageMessage("The Admin Has Been Created Successfully", 1);
        return redirect()->route("admin.admins.index");
    }



    public function profile(){
        $data['user'] = auth()->user();
        return view("admin.profile", $data);
    }

    public function saveProfile(Request $request){

        $rules = $this->rules();
        $user = auth()->user();
        $rules['role'] =[];
        if(empty($request->password) && empty($request->password) && empty($request->current_password)){
            $rules["password"] = [];
            $rules["current_password"] = [];
        }else{
            $rules["current_password"] = ["required", new HashMatching($user->password)];
        }
        if(!empty($request->file("profile_photo")))
            $rules["profile_photo"] = ["file", "mimes:jpg,jpeg,png,bmp","max:512"];

        if($user->username === $request->username)
            $rules["username"] = [];
        if($user->email === $request->email)
            $rules["email"] = [];


        $request->validate($rules);
        $user->full_name = $request->full_name;
        $user->username = $request->username;
        $user->email = $request->email;
        if(isset($request->password) && !empty($request->password))
            $user->password = $request->password;
        if(!empty($request->file("profile_photo"))){
            $file = $user->directory_path . $user->profile_photo;
            dd($file);
            if(File::exists($file)) {
                File::delete($file);
            }
            $photo_name = $this->upload($request->file("profile_photo"),$user->directory_path);
            $user->profile_photo = $photo_name;
        }
        $user->save();
        $this->setPageMessage("The Profile Information Has Been Updated Successfully", 1);
        return redirect()->route("admin.profile.index");
    }

        public function destroy(Request $request, $id){
        if(!hasPermissions("admin-control"))
            abort("401");
//            dd($request->id);
        Admin::findOrFail($request->id)->delete();
        $this->setPageMessage("The User Has Been Deleted Successfully", 0);
        return redirect()->route("admin.admins.index");

    }

}
