<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AppSetting;
use App\Models\ButtonSetting;
use Database\Seeders\MainButton;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AppSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \string[][]
     */


    protected function rules(){
        return [
            "Terms_And_Conditions" => ["required"],
        ];
    }

    public function Setting(){
        if(!hasPermissions("setting-promo-code"))
            abort("401");

        $AppSetting =AppSetting::all();
        return view("admin.AppSetting.promo-code",compact('AppSetting'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        return view("admin.AppSetting.create");
    }


    public function ShowMainButton(){
        $data["Buttons"]=ButtonSetting::where("type",1)->get();
        return view('admin.AppSetting.MainButton',$data);
    }

    public function ButtonChatHistory(){
        $data["Buttons"]=ButtonSetting::where("type",2)->get();
        return view('admin.AppSetting.button-chat-history',$data);
    }

    public function ConsultationHistory(){
        $data["Buttons"]=ButtonSetting::where("type",3)->get();
        return view('admin.AppSetting.ConsultationHistory',$data);
    }

    public function UpdateButton(Request $request){
        $id=$request->id;
        $redirect=$request->redirect;
        $button=ButtonSetting::find($id);
        $button->title_en=$request->title_en ?? $button->title_en;
        $button->description_en=$request->description_en ?? $button->description_en;
        $button->title_ar=$request->title_ar ?? $button->title_ar;
        $button->description_ar=$request->description_ar ?? $button->description_ar;
        $button->save();
        return redirect()->route($redirect);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        $valid = Validator::make($request->all(), $this->rules());
//        if($valid->fails()){
//            return redirect()->route("admin.adminRole.create")->withInput($request->all())->withErrors($valid->errors()->messages());
//        }
//
//        $Admin= new Admin();
//        $Admin->is_super_admin= 0;
//        $Admin->full_name= $request->full_name;
//        $Admin->username = $request->username;
//        $Admin->email = $request->email;
//        $Admin->password = Hash::make($request->password);
//        $Admin->save();
//
//        return redirect()->route("admin.adminRole.index");

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $Admin=Admin::find($request->id);
        return view("admin.AppSetting.show",$Admin);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
//        dd($request->id);
        $EditSetting=AppSetting::find(1);

        return view("admin.AppSetting.edit",$EditSetting);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
       AppSetting::first()->update([
            'Terms_And_Conditions' => $request->Terms_And_Conditions,
            'contest' => $request->contest,
            'updated_at' =>now(),
        ]);

        return redirect()->route("admin.App-Setting.Setting");
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request  $request)
    {
//        dd($request->id);
        $delete = Admin::find($request->id)->delete();
        return Redirect()->back()->with('success','Admin Soft Delete Successfully');
    }
}
