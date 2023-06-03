<?php

namespace App\Http\Controllers\Web\Ajax;

use App\Http\Controllers\Controller;
use App\Models\AddOnsOption;
use Illuminate\Http\Request;

class AddOnsOptionsController extends Controller
{

    public function destroy(Request $request)
    {
        $data["status"] = 0;
        $option = AddOnsOption::find($request->optionId);
        if($option){
            $totalOptions = AddOnsOption::where("add_on_id", $option->add_on_id)->count();
            if($totalOptions > 1){
                $option->delete();
                $data["status"] = 1;
            }
        }
        return response()->json($data);

    }
}
