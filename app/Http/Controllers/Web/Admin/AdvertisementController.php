<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\DangerMessage;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Http\Controllers\Controller;
use App\Http\Resources\ads\ShowAdsResource;
use App\Models\Ads;
use App\Models\Ads_text;
use App\Models\LoadingAds;
use App\Traits\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdvertisementController extends Controller
{
    use Helper;
    protected function rules(){
        return [
            "type" => ["required","in:1,2,3,4"],
            "Ads_photo" => ["required_if:type,2"],
            "Ads_photo.*"=>["mimes:jpeg,jpg,png,bmp,gif,svg"],
            "Text" => ["required_if:type,1"],
            "bg_color" => ["required_if:type,1"],
            "Ads_video" => ["required_if:type,3","mimes:mp4,mov,ogg,qt"],
            "logo" => ["required","mimes:jpeg,jpg,png,bmp,gif,svg"]

        ];
    }

    protected function ruleStatus(){
       return ["status" => ["required","in:0,1"]];
    }
    public function index()
    {
        $Ads["ads"]=Ads::all();
        $data["ads"] = ShowAdsResource::collection($Ads);
        return view("admin.advertisement.index",$Ads);
    }

    /////////////  Loading Ads /////////////////////
    public function LodingAds()
    {
        $Ads["ads"]=LoadingAds::all();
        return view("admin.advertisement.LoadingAds",$Ads);
    }

    public  function storeLoadingAds(Request $request){

        $valid = Validator::make($request->all(), ["Ads_photo"=>["required","image","max:500"]]);
        if($valid->fails()){
            return redirect()->route("admin.Ads.create-loading-ads")->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $LoadingAds= new LoadingAds();
        $LoadingAds->Status= 1;
        $LoadingAds->save();
        if($request->hasfile('Ads_photo'))
            $LoadingAds->saveMedia($request->file("Ads_photo"),'LoadingAds');
        return redirect()->route("admin.Ads.loadingAds");
    }

    public function createloadingAds()
    {
        return view("admin.advertisement.createLoadingAds");
    }


    public function EditLoadingAds(Request  $request)
    {
        $data["ads"]=LoadingAds::find($request->id);
        return view("admin.advertisement.EditLoadingAds",$data);
    }

    public function updateLoadingAds(Request $request)
    {
        $valid = Validator::make($request->all(), ["Ads_photo"=>["image","max:500"]]);
        if($valid->fails()){
            return redirect()->route("admin.Ads.Edit-loading-ads",["id"=>$request->id])->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        //
        $ads_id = $request->id;
        $ads = LoadingAds::find($ads_id);
        $ads->status = $request->status =="on"?1:0;
        $ads->save();
        if($request->file("Ads_photo")){
            $ads->removeAllGroupFiles("LoadingAds");
            if($request->file("Ads_photo"))
                $ads->saveMedia($request->file("Ads_photo"),'LoadingAds');
        }

        return redirect()->route("admin.Ads.loadingAds");
    }


    /////////////  Loading Ads /////////////////////


    public function create()
    {
        return view("admin.advertisement.create");
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {


        $data=[];
        $valid = Validator::make($request->all(), $this->rules());
        if($valid->fails()){
            return redirect()->route("admin.Ads.create")->withInput($request->all())->withErrors($valid->errors()->messages());
        }

        $Ads= new Ads();
        $Ads->status= 1;
        $Ads->type= $request->type;
        $Ads->save();
        if($request->hasfile('logo'))
                $Ads->saveMedia($request->file("logo"),'Logo');

        if($request->type == 1)
        {
                    $ads = new Ads_text();
                    $ads->Data = $request->Text;
                    $ads->ads_id = $Ads->id;
                    $ads->background_color=str_replace("#", "",$request->bg_color) ?? "FFFF";
                    $ads->save();
                    $data["text"]=  $request->Text;
        }
        elseif($request->type == 2) {
            if($request->hasfile('Ads_photo'))
                foreach($request->Ads_photo as $file){
                    $Ads->saveMedia($file,'Ads');
                }

        }elseif($request->type == 3){
            if($request->hasfile('Ads_video'))
                $Ads->saveMedia($request->file('Ads_video'),'Ads');

        }elseif ($request->type == 4){
            if($request->hasfile('Ads_mp3'))
                $Ads->saveMedia($request->file('Ads_mp3'),'Ads');
        }





        foreach ($Ads->getFirstMediaFile('Ads') as $file){
            $data[]=$file->url;
        }

        $url = env("NODEJSURL") . '/addAds';
        $this->sendRequest('post', [
            "id" => $Ads->id,
            "type" => $Ads->type == 4 ?3:$Ads->type,
            "data" => $data,
            "logo" =>$Ads->getFirstMediaFile('Logo') != null ? $Ads->getFirstMediaFile('Logo')->url : ""

        ], $url);

        return redirect()->route("admin.Ads.index");


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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        //
        $data["ads"] = Ads::where(["id" => $request->id])->firstOrFail();
        return view("admin.advertisement.edit",$data);
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
        //
        $ads_id = $request->id;
        $ads = Ads::findOrFail($ads_id);
        $ads->status = $request->status =="on"?1:0;
        $ads->save();
        if(isset($request->Text)){
            $ads_text=Ads_text::find($ads->Ads_text->id);
            $ads_text->Data = $request->Text ?? $ads->Text;
            $ads_text->save();
        }


        if($request->file("Ads_photo")){
            if($request->hasfile('Ads_photo'))
                $ads->removeMedia($ads->getFirstMediaFile("Ads"));
                foreach($request->Ads_photo as $file){
                    $ads->saveMedia($file,'Ads');
                }
        }

        if($request->file("logo")){
            $ads->removeMedia($ads->getFirstMediaFile("Logo"));
            if($request->file("logo"))
                $ads->saveMedia($request->file("logo"),'Logo');
        }

        if($request->file("Ads_video")){
            $ads->removeMedia($ads->getFirstMediaFile("Ads"));
            if($request->file("Ads_video"))
                $ads->saveMedia($request->file("Ads_video"),'Ads');
        }

        if($request->file("Ads_mp3")){
            $ads->removeMedia($ads->getFirstMediaFile("Ads"));
            if($request->file("Ads_mp3"))
                $ads->saveMedia($request->file("Ads_mp3"),'Ads');
        }




        return redirect()->route("admin.Ads.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $ads = Ads::find($request->id);
        if($ads->delete()){
            $ads->removeAllFiles();
        }
        $message = (new DangerMessage())->title("Deleted Successfully")
            ->body("The Advertisement Has Been Deleted Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.Ads.index");
    }

    public function loadingAdsdestroy(Request $request)
    {

        $ads = LoadingAds::find($request->id);
        if($ads->delete()){
            $ads->removeAllFiles();
        }
        $message = (new DangerMessage())->title("Deleted Successfully")
            ->body("The Advertisement Has Been Deleted Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.Ads.loadingAds");
    }



}
