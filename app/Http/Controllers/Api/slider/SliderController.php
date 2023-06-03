<?php

namespace App\Http\Controllers\Api\slider;
use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Http\Resources\slider\ShowSliderResource;
use App\Http\Resources\slider\SliderResource;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $sliders=Slider::where("status",1)->get();
       $data=[];
       foreach($sliders as $slider ){
           $data[] = $slider->getFirstMediaFile('slider_photo')->url ?? " ";
       }

    //   $data = SliderResource::collection(ShowSliderResource::class,$data);
       return JsonResponse::data(["status" => 1,"sliders"=>$data])->send();

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $slider= new Slider();
        $slider->save();
        if($request->hasfile('slider_photo'))
            $slider->saveMedia($request->file('slider_photo'),'slider_photo');
        return JsonResponse::success()->send();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
