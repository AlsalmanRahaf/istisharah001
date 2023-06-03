<?php

namespace App\Http\Controllers\Web\Admin;


use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Traits\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Input\Input;

class RoleController extends Controller
{

    use Helper;

    public function index(){
        if(!hasPermissions("admin-control"))
            abort("401");
        $data['roles'] = Role::all();
        return view("admin.roles.index",$data);
    }

    public function create(){
        if(!hasPermissions("admin-control"))
            abort("401");
        $data["permissions"]= Permission::all();
        if(Session::has("permissions")){
            $data["permissionsSelected"] = Session::get("permissions");
            Session::forget("permissions");
        }

        return view("admin.roles.create",$data);
    }

    public function store(Request $request){
        if(!hasPermissions("admin-control"))
            abort("401");

        $valid = Validator::make($request->all(), ["name" => "required|max:255|unique:admin_roles"]);
        if($valid->fails()){
            Session::put("permissions",array_flip($request->permissions));
            return redirect()->route("admin.roles.create")->withErrors($valid->errors())->withInput($request->all());
        }

        $role = new Role();
        $role->name = $request->name;
        $role->save();
        if($request->permissions)
            $role->permissions()->sync($request->permissions);
        $this->setPageMessage("The Role Has Been Created Successfully");
        return redirect()->route("admin.roles.index");
    }

    public function edit(Request $request){
        if(!hasPermissions("admin-control"))
            abort("401");


        $role = Role::findOrFail($request->id);
        $data["permissions"]= Permission::all();
        $rolePermissions = [];
        foreach ($role->permissions as $permission){
            $rolePermissions[$permission->id] = true;
        }

        if(Session::has("permissions")){
            $data["permissionsSelected"] = Session::get("permissions");
            Session::forget("permissions");
        }
        $data["rolePermissions"] = $rolePermissions;
        $data["role"] = $role;

        return view("admin.roles.edit",$data);
    }

    public function update (Request $request){
        if(!hasPermissions("admin-control"))
            abort("401");
        $id=$request->id;
        $role = Role::findOrFail($id);
        $rules = ["name" => "required|max:255"];
        if($role->name != $request->name)
            $rules = ["name" => "required|max:255|unique:admin_roles"];
        $valid = Validator::make($request->all(), $rules);
        if($valid->fails()){
            Session::put("permissions",array_flip($request->permissions));
            return redirect()->route("admin.roles.edit",$role->id)->withErrors($valid->errors())->withInput($request->all());
        }

        $role->name = $request->name;
        $role->save();
        if($request->permissions)
            $role->permissions()->sync($request->permissions);
        $this->setPageMessage("The Role Has Been Updated Successfully");
        return redirect()->route("admin.roles.index");
    }

    public function destroy(Request $request){
        if(!hasPermissions("admin-control"))
            abort("401");
        $id=$request->id;
        Role::find($id)->delete();
        $this->setPageMessage("The Role Has Been Deleted Successfully",0);
        return redirect()->route("admin.roles.index");
    }



    public function CreatePermissions(){
        if(!hasPermissions("admin-control"))
            abort("401");
        return view("admin.roles.createpermissions");
    }

    public function StorePermissions(Request $request){
        if(!hasPermissions("admin-control"))
            abort("401");
        $request->validate([
            'name' => 'required|max:255|unique:admin_permissions,name',
        ]);

        Permission::insert([
        'name' => $request->name,
       'slug' => strtolower(str_replace(' ', '-',$request->name))
        ]);

        $this->setPageMessage("The Role Has Been Created Successfully");
        return redirect()->back();
    }

}
