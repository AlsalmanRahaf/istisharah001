<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\DangerMessage;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Item;
use App\Models\Slider;
use App\Models\SliderMarket;
use App\Rules\AlphaSpace;
use App\Rules\ArAlphaSpace;
use App\Rules\CheckStatus;
use App\Traits\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class SliderController extends Controller
{

    use Helper;

    public function rules(){
        return [
            "type" => ["required", Rule::in(["1","2"])],
            "slider_market_photo" => ["required", "file", "mimes:jpg,jpeg,png,bmp","max:512"],
            "branch" => ["required"]
        ];
    }

    public function index()
    {
        $data['sliders'] = SliderMarket::all();
        return view("admin.sliders_market.index", $data);
    }


    public function create()
    {
        $data["categories"] = Category::all();
        $data["items"] = Item::all();
        $data["branches"] = Branch::all();
        return view("admin.sliders_market.create",$data);
    }


    public function store(Request $request)
    {
        $rules = $this->rules();
        if(isset($request->type)){
            if($request->type == 1){
                $rules["category"] = ["required", "exists:categories,id", new CheckStatus("App\Models\Category","status")];
                $request->request->add(["navigate_id" => $request->category]);
                $request->request->remove('item');
            }else if($request->type == 2){
                $rules["item"] = ["required", "exists:items,id", new CheckStatus("App\Models\Item","status")];
                $request->request->add(["navigate_id" => $request->item]);
                $request->request->remove('category');
            }
        }
        $request->validate($rules);


        $slider = new SliderMarket();
        $slider->Status = 1;
        $slider->type = $request->type;
        $slider->navigate_id = $request->navigate_id;
        $slider->branch_id = $request->branch;
        $slider->save();
        $slider->saveMedia($request->file("slider_market_photo"), "slider_market_photo");

        $message = (new SuccessMessage())->title("Created Successfully")
            ->body("The Slider Has Been Created Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.slider_market.index");
    }



    public function edit($lang, $id)
    {
        $data['slider'] = $slider = SliderMarket::findOrFail($id);
        $data["categories"] = Category::all();
        $data["items"] = Item::all();

        if($slider->type == 2){
            $item = $slider->navigator;
            $branches = $item->branches;
        }else{
            $branches = DB::table("items")->select("branches.*")
                ->join("item_branches", "item_branches.item_id","=","items.id")
                ->join("branches", "branches.id","=","item_branches.branch_id")
                ->where("items.category_id", $id)->groupBy("branches.id")->get();
        }

        $data['branches'] = !$branches->isEmpty() ? $branches : Branch::all();

        return view("admin.sliders_market.edit",$data);
    }


    public function update(Request $request, $lang, $id)
    {
        $rules = $this->rules();

        if($request->request->has('type')){
            if($request->type == 1){
                $rules["category"] = ["required", "exists:categories,id",  new CheckStatus("App\Models\Category","status")];
                if($request->request->has('item'))
                    $request->request->remove('item');
            }
            else if($request->type == 2){
                $rules["item"] = ["required", "exists:items,id", new CheckStatus("App\Models\Item","status")];
                if($request->request->has('category'))
                    $request->request->remove('category');
            }
        }

        if(empty($request->file("slider_market_photo"))){
            $rules["slider_market_photo"] = [];
        }


        $request->validate($rules);

        if($request->type == 1)
            $request->request->add(["navigate_id" => $request->category]);
        else if($request->type == 2)
            $request->request->add(["navigate_id" => $request->item]);

        $slider = SliderMarket::findOrFail($id);

        $slider->Status = isset($request->Status) ? 1 : 2;
        $slider->type = $request->type;
        $slider->branch_id = $request->branch;
        $slider->navigate_id = $request->navigate_id;
        $slider->save();
        if ($request->hasFile("slider_market_photo")){
            $slider->removeMedia($slider->getFirstMediaFile("slider_market_photo"));
            $slider->saveMedia($request->file("slider_market_photo"), "slider_market_photo");
        }

        $message = (new SuccessMessage())->title("Updated Successfully")
            ->body("The Slider Has Been Updated Successfully");
        Dialog::flashing($message);

        return redirect()->route("admin.slider_market.index");
    }


    public function destroy($lang, $id)
    {
        $slider = SliderMarket::findOrFail($id);
        $slider->removeAllFiles();
        $slider->delete();

        $message = (new DangerMessage())->title("Deleted Successfully")
            ->body("The Slider Has Been Deleted Successfully");
        Dialog::flashing($message);

        return redirect()->route("admin.slider_market.index");
    }
}
