<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ManagerAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    protected function rules(){
        return [
            "full_name" => ["required"],
            "username" => ["required"],
            "email" => ["required"],
            "password" => ["required"]
        ];
    }

    public function index()
    {
        $Admins=Admin::where('deleted_at',null)->get();
//        dd($Admins);
        return view("admin.ManagerAdmin.index",compact('Admins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("admin.ManagerAdmin.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $valid = Validator::make($request->all(), $this->rules());
        if($valid->fails()){
            return redirect()->route("admin.adminRole.create")->withInput($request->all())->withErrors($valid->errors()->messages());
        }

        $Admin= new Admin();
        $Admin->is_super_admin= 0;
        $Admin->full_name= $request->full_name;
        $Admin->username = $request->username;
        $Admin->email = $request->email;
        $Admin->password = Hash::make($request->password);
        $Admin->save();

        return redirect()->route("admin.adminRole.index");

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
//        dd($request->id);
        $Admin=Admin::find($request->id);
        return view("admin.ManagerAdmin.show",$Admin);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
//        dd($request->id);
        $Admin=Admin::find($request->id);
        return view("admin.ManagerAdmin.edit",$Admin);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        Admin::find($request->id)->update([
            'full_name' => $request->full_name,
            'username' =>$request->username,
            'email' =>$request->email,
        ]);
        if ($request->password)
        {
            Admin::find($request->id)->update([
                'full_name' => $request->full_name,
                'username' =>$request->username,
                'email' =>$request->email,
                'password' => Hash::make($request->password)
            ]);
        }
        $Admins=Admin::where('deleted_at',null)->get();
        return view("admin.ManagerAdmin.index",compact('Admins'));
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request  $request)
    {
//        dd($request->id);
        $delete = Admin::find($request->id)->delete();
        return Redirect()->back()->with('success','Admin Soft Delete Successfully');
    }
}
