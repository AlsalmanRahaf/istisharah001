<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WelcomeMessage;
use App\Traits\Helper;


class WelcomeMessageController extends Controller
{
      use Helper;
      protected function rules(){
        return [
            "TextMessage_En"         => ["required"],
            "TextMessage_Ar"          => ["required"],
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['WelcomeMessages']= WelcomeMessage::all();
        return view('admin.WelcomeMessage.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
         return view("admin.WelcomeMessage.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate($this->rules());
        $WelcomeMessage=new WelcomeMessage;
        $WelcomeMessage->TextMessage_En = $request->TextMessage_En;
        $WelcomeMessage->TextMessage_Ar =$request->TextMessage_Ar;
        $WelcomeMessage->save();
        $message = (new SuccessMessage())->title("Create Message Successfully")
            ->body(".");
        Dialog::flashing($message);

        return redirect()->route("admin.WelcomeMessage.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit($lang,$id)
    {
        $data['WelcomeMessage'] = WelcomeMessage::find($id);
        return view("admin.WelcomeMessage.edit",$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request,$lang,$id)
    {
        $request->validate( $this->rules());
            $WelcomeMessage = WelcomeMessage::find($id);
            $WelcomeMessage->TextMessage_En = $request->TextMessage_En;
            $WelcomeMessage->TextMessage_Ar =$request->TextMessage_Ar;
            $WelcomeMessage->save();
         $this->setPageMessage("The Welcome Message  Has Been Updated Successfully", 1);

        $message = (new SuccessMessage())->title("Message Has Been Updated Successfully")
            ->body(".");
        Dialog::flashing($message);
        return redirect()->route("admin.WelcomeMessage.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($lang,$id)
    {
        if(WelcomeMessage::findOrFail($id)->delete())
            $message = (new SuccessMessage())->title("Message Has Been Deleted Successfully")
                ->body(".");
            Dialog::flashing($message);
        return redirect()->route("admin.WelcomeMessage.index");

    }
}