<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::all();
        $data = [];
        foreach ($categories as $category) {
            $data[] = [
                "id" => $category->id,
                "name" => $category->name,
                "image" => $category->getFirstMediaFile() ? $category->getFirstMediaFile()->url : null,
            ];
        }
        return $data;
    }
}
