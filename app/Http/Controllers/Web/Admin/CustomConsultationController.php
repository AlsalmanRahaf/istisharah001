<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\DangerMessage;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Http\Controllers\Controller;
use App\Models\Consultant_admin_chat;
use App\Models\CustomConsultation;
use App\Models\Room;
use App\Models\User;
use App\Traits\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use function PHPUnit\Framework\isNull;

class CustomConsultationController extends Controller
{
    //
    use Helper;


    private  $consultations;

    public function check_consultatnt($user_id,$consultation_name){
       return DB::table('custom_consultations')->where('consultant_id', $user_id )->where('consultation_name_en', $consultation_name)->exists();
    }
    public function __construct()
    {
        $this->consultations = CustomConsultation::all();
    }

    public  function  Rules(){
        return [
            "user_ids" => ['required'],
            "consultation_name_en" => ["required","unique:custom_consultations"],
            "consultation_name_ar" => ["required","unique:custom_consultations"],
        ];
    }

    public function index()
    {
        $data["consultations"] = CustomConsultation::all();
        return view('admin.consultations.custom_consultations',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::where('type','u')->get();
        return view('admin.consultations.create_consultation',compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $valid = Validator::make($request->all(),$this->Rules());
        if($valid->fails()){
            return redirect()->route("admin.consultations.Create")->withInput($request->all())->withErrors($valid->errors()->messages());
        }
            $user_ids = $request->user_ids;
            $consultation_name_en = $request->consultation_name_en;
            $consultation_name_ar = $request->consultation_name_ar;
            foreach ($user_ids as $id) {
                if (!$this->check_consultatnt($id, $consultation_name_en)) {
                    $CustomConsultation = new CustomConsultation();
                    $CustomConsultation->consultation_name_ar = $consultation_name_ar;
                    $CustomConsultation->consultation_name_en = $request->consultation_name_en;
                    $CustomConsultation->consultant_id = $id;
                    $CustomConsultation->status = 1;
                    if($CustomConsultation->save()){
                        $url=env("NODEJSURL").'/change_user_to_other_consultant';
                        $this->sendRequest('post',[
                            'user_id' => $id,
                            'type' =>$consultation_name_en
                        ],$url);
                    }
                }
            }



            $message = (new SuccessMessage())->title("Successfully")
                ->body("The consultant Has Been added Successfully");
            Dialog::flashing($message);

        return redirect()->route("admin.consultations.Custom");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
        $consultant_id=$request->consultant_id;
        $consultant=CustomConsultation::find($consultant_id);
        $consultant->status=0;
        $consultant->save();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id=$request->id;
        CustomConsultation::destroy($id);
        $message = (new SuccessMessage())->title("Successfully")
            ->body("Record  Has Been Deleted Successfully");
        Dialog::flashing($message);

        return redirect()->route("admin.consultations.Custom");
    }
}
