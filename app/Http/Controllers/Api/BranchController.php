<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\BranchResource;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index(Request $request){
        $branches = Branch::where("category_id", $request->category_id)->get();

        foreach ($branches as $branch) {
            $data[] = [
                "id" => $branch->id,
                "store_name" => $branch->store_name,
                "latitude" => $branch->latitude,
                "longitude" => $branch->longitude,
                "address" => $branch->address,
                "img_url" => $branch->getFirstMediaFile() ? $branch->getFirstMediaFile()->url : null,
            ];
        }

        return $data;
    }
}
