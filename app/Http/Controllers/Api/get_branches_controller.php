<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class get_branches_controller extends Controller
{
    //
    public function getAllBranches(){

        $branches = Branch::all();
        $data = [];
        foreach ($branches as $index => $branch) {
            $data[$index]['id'] = $branch->id;
            $data[$index]['store_name'] = $branch->store_name;
            $data[$index]['latitude'] = $branch->latitude;
            $data[$index]['longitude'] = $branch->longitude;
            $data[$index]['address'] = $branch->address;
            $data[$index]['img_url'] = $branch->getFirstMediaFile() ? $branch->getFirstMediaFile()->url : Null;
            $data[$index]['InvoiceFooterMessage '] = Null;
            $data[$index]['phone_number'] = $branch->phone_number;
            $data[$index]['category_id '] = $branch->category_id ;
        }

        return response($data);

    }

    public function getBranchDetails(Request $request){
        $branch = Branch::find($request->id);
        $data['id'] = $branch->id;
        $data['store_name'] = $branch->store_name;
        $data['latitude'] = $branch->latitude;
        $data['longitude'] = $branch->longitude;
        $data['address'] = $branch->address;
        $data['img_url'] = $branch->getFirstMediaFile() ? $branch->getFirstMediaFile()->url : Null;
        $data['InvoiceFooterMessage '] = Null;
        $data['phone_number'] = $branch->phone_number;
        $data['category_id '] = $branch->category_id ;
        return response([$data]);

    }
}
