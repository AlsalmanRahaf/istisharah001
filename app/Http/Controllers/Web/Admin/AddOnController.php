<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\DangerMessage;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Http\Controllers\Controller;
use App\Models\AddOns;
use App\Models\AddOnsOption;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;


class AddOnController extends Controller
{
    public function rules(){
        return [
            "add_ons_name_en"   => ["required"],
            "add_ons_name_ar"   => ["required"],
            "choice_type"       => ["required", "in:1,2"],
            "add_ons_list_en.*" => ["required"],
            "add_ons_list_ar.*" => ["required"],
            "price.*"           => ["required", "numeric", "min:0"]

        ];
    }

    public function attributes(){
        return [

            "add_ons_list_en.*"   => "English name",
            "add_ons_list_ar.*"   => "Arabic name",
            "price.*"             => "Price",

        ];
    }

    public function index(Request $request){
        $data['item'] = Item::find($request->item_id);
        $data['addOns'] = AddOns::where("item_id", $request->item_id)->get();
        return view("admin.add_ons.index", $data);
    }


    public function create(Request $request)
    {
        $data["item"] = Item::findOrFail($request->item_id);
        if(Session::has("options_count")){
            //var_dump(Session::get("options_count"));die;
            $data["options_selected"] = Session::get("options_count");
            Session::remove("options_count");
        }
        return view("admin.add_ons.create",$data);
    }


    public function store(Request $request)
    {
        $rules = $this->rules();
        $valid = Validator::make($request->all(), $rules);
        $valid->setAttributeNames($this->attributes());
        $numberOptions = count($request->add_ons_list_en);

        if($valid->fails()){
            Session::put("options_count" , $numberOptions);
            return redirect()->back()->withErrors($valid->errors())->withInput($request->all());
        }else{
            $addOn = new AddOns();
            $addOn->name_en = $request->add_ons_name_en;
            $addOn->name_ar = $request->add_ons_name_ar;
            $addOn->item_id = $request->item_id;
            $addOn->type_input = $request->choice_type;
            if($addOn->save()){
                for ($i=0; $i < $numberOptions;$i++){
                    $option = new AddOnsOption([
                        "name_en"   => $request->add_ons_list_en[$i],
                        "name_ar"   => $request->add_ons_list_ar[$i],
                        "price"             => $request->price[$i],
                    ]);
                    $addOn->options()->save($option);
                }
            }

        }
        $message = (new SuccessMessage())->title(__('Created Successfully'))
            ->body(__("The Add Ons Has Been Created Successfully"));
        Dialog::flashing($message);

        return redirect()->route("admin.items.add_ons.index", ["item_id" => $request->item_id]);
    }


    public function edit(Request $request)
    {
        if(!hasPermissions("edit-add-on's" ))
            return abort("401");

        $data["item"] = Item::findOrFail($request->item_id);
        $data["addOn"] = AddOns::findOrFail($request->id);
        if(Session::has("options_count")){
            //var_dump(Session::get("options_count"));die;
            $data["options_selected"] = Session::get("options_count");
            Session::remove("options_count");
        }
        return view("admin.add_ons.edit",$data);
    }


    public function update(Request $request, $item_id, $id)
    {
        $rules = $this->rules();
        $valid = Validator::make($request->all(), $rules);
        $valid->setAttributeNames($this->attributes());
        if(!isset($request->add_ons_list_en)){
            $rules = [
                "add_ons_list_en.*" => [],
                "add_ons_list_ar.*" => [],
                "price.*"           => []
            ];
        }else{

            $numberOptions = count($request->add_ons_list_en);
        }

        if($valid->fails()){
            if(isset($numberOptions)){
                Session::put("options_count" , $numberOptions);
            }
            return redirect()->back()->withErrors($valid->errors())->withInput($request->all());
        }else{
            $addOn = AddOns::findOrFail($request->id);
            $addOn->name_en = $request->add_ons_name_en;
            $addOn->name_ar = $request->add_ons_name_ar;
            $addOn->item_id = $request->item_id;
            $addOn->type_input = $request->choice_type;
            if($addOn->save()){
                if(isset($numberOptions)){
                    for ($i=0; $i < $numberOptions;$i++){
                        $option = new AddOnsOption();
                        $option->name_en = $request->add_ons_list_en[$i];
                        $option->name_ar = $request->add_ons_list_ar[$i];
                        $option->price = $request->price[$i];
                        $option->add_on_id = $addOn->id;
                        $option->save();
                    }
                }
            }

        }

        $message = (new SuccessMessage())->title(__('Updated Successfully'))
            ->body(__("The Add Ons Has Been Updated Successfully"));
        Dialog::flashing($message);

        return redirect()->route("admin.items.add_ons.index", ["item_id" => $request->item_id]);
    }


    public function destroy(Request $request)
    {
        $addOn = AddOns::findOrFail($request->id);
        foreach ($addOn->options as $option){
            $option->delete();
        }
        $addOn->delete();

        $message = (new DangerMessage())->title(__('Deleted Successfully'))
            ->body(__("The Add Ons Has Been Deleted Successfully"));
        Dialog::flashing($message);

        return redirect()->route("admin.items.add_ons.index", ["item_id" => $request->item_id]);
    }
}
