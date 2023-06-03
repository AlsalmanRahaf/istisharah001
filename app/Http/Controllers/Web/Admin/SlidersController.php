<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\DangerMessage;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Http\Controllers\Controller;
use App\Traits\Helper;
use Illuminate\Http\Request;
use App\Models\Slider;
use Illuminate\Support\Facades\Validator;

class SlidersController extends Controller
{
    //

    use Helper;
    protected function rules(){
        return [
            "slider_photo" => ["required"],
            "slider_photo.*" => ["required","mimes:jpeg,png","max:512","image"],

        ];
    }

    public function index(){

        if(!hasPermissions("admin-control"))
            abort("401");
        $data["sliders"]=$this->getSliderData(Slider::all());
        return view("admin.sliders.index",$data);
    }

    public function create(Request $request)
    {
        return view("admin.sliders.create");
    }

    public function edit($lang,$id)
    {
        $data["Slider"] =Slider::find($id)->first();
        return view("admin.sliders.edit",$data);
    }

    public function update(Request  $request,$lang,$id)
    {


        if(Slider::find($id)->exists()){
            $slider=Slider::find($id)->first();

            $slider->status = $request->status == "on" ? 1 : 0;
            $slider->save();
            if($request->file("slider_photo")){
                $slider->removeAllGroupFiles("slider_photo");
                if($request->file("slider_photo"))
                    $slider->saveMedia($request->file("slider_photo"),'slider_photo');
            }

        }

        $message = (new SuccessMessage())->title("Updated Successfully")
            ->body("The Slider Has Been Updated Successfully");
        Dialog::flashing($message);

        return redirect()->route("admin.sliders.index");

    }


    public function cancel(){
        return redirect()->route("admin.sliders.index");
    }


    public function store(Request $request){

        $valid = Validator::make($request->all(), $this->rules());
        if($valid->fails()){
            return redirect()->route("admin.sliders.create")->withInput($request->all())->withErrors($valid->errors()->messages());
        }

        foreach ($request->file("slider_photo") as $file){
            $slider= new Slider();
            $slider->save();
            $slider->saveMedia($file,'slider_photo');
            $image = $slider->getFirstMediaFile('slider_photo')->url;
            if($image != null) {
                $url = env("NODEJSURL") . '/addSlider';
                $this->sendRequest('post', [
                    'image' => $image,
                ], $url);
            }
        }

        return redirect()->route("admin.sliders.index");
    }

    public function destroy($lang,$slider_id){

        $slider=Slider::find($slider_id);

        if($slider->exists()){

            $slider->delete();
            $message = (new SuccessMessage())->title("Deleted Successfully")
                ->body("Slider has been deleted");
            Dialog::flashing($message);

        }else{

            $message = (new DangerMessage())->title("Deleted UnSuccess")
                ->body("The Slider Has Been Deleted Successfully");
            Dialog::flashing($message);
        }

        return redirect()->route("admin.sliders.index");

    }


    public function getSliderData($sliders){

        $data=[];
        foreach ($sliders as $slider){
             $slider["url"]= $slider->getFirstMediaFile('slider_photo') != null ? $slider->getFirstMediaFile('slider_photo')->url :"";
             $data[]=$slider;
        }
        return $data;

    }


}
