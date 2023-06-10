<?php

namespace App\Http\Controllers\Web\Admin;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Helpers\Dialog\Web\Dialog;

use App\Http\Controllers\Controller;
use App\Models\ConsultantRequest;
use Illuminate\Http\Request;

class FormRegisterController extends Controller
{
    public function index(Request $request)
    {
        $consultantrequests=ConsultantRequest::all();
        return view("admin.form_register.index", compact('consultantrequests'));
          }
    public function accept(Request $request)
    {
        $consultant = ConsultantRequest::find($request->id);
        $consultant->accept=$request->accept;
        $consultant->save();
        if($request->accept==1){
        $message = (new SuccessMessage())->title("Accept Successfully")
            ->body("The consultant Has Been Accepted Successfully");
        Dialog::flashing($message);
            }else{
            $message = (new SuccessMessage())->title("Not Accept Successfully")->body("The consultant Has Been Not Accepted Successfully");
            Dialog::flashing($message);
        }
        return redirect()->route("admin.consultantrequest.index");
    }
//    public function not_accept(Request $request)
//    {
//        $consultant = ConsultantRequest::find($request->id);
//        $consultant->accept = 0;
//        $consultant->save();
//
//        $message = (new SuccessMessage())->title("Not Accept Successfully")
//            ->body("The consultant Has Been Not Accepted Successfully");
//        Dialog::flashing($message);
//
//        return redirect()->route("admin.consultantrequest.index");
//    }
//    public function index (Request $request){
//        $this->permissionsAllowed( "view-institution-requests-join");
//
//        $data['users'] = User::join("institutions", "institutions.user_id", "=", "users.id")
//            ->where("type", "u-in")->where("institutions.accept", 0)->get("users.*");
//        return view("admin.form_register.institutions", $data);
//    }

}
