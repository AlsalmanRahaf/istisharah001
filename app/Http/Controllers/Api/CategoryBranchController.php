<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryBranchResource;
use App\Models\CategoryBranch;
use Illuminate\Http\Request;

class CategoryBranchController extends Controller
{
    public function index(){
        $categories = CategoryBranch::all();
        $data = [];
        foreach ($categories as $category) {
            $data[] = [
                "id" => $category->id,
                "img_url" => $category->getFirstMediaFile() ? $category->getFirstMediaFile()->url : null,
            ];
        }
        return $data;
    }
}
