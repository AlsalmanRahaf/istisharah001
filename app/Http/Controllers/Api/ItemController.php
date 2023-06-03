<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function getByBranchAndCategory(Request $request){
      $items = Item::join("item_branches", "item_branches.item_id", "=", "items.id")
         ->where([["category_id", $request->category_id],["item_branches.branch_id", $request->branch_id]])
         ->select("items.*")->get();

     $data = [];
      foreach ($items as $item){
          $itemData = [];
          $itemData["id"] = $item->id;
          $itemData["name"] = $item->name;
          $itemData["price"] = $item->price;
          $itemData["description"] = $item->getDescriptionAttribute();
          $itemData["image_url"] = $item->getFirstMediaFile("main_photo") ? $item->getFirstMediaFile("main_photo")->url : Null;
          $itemData["status"] = $item->status;


          $data[] = $itemData;
      }
      return $data;
    }
}
