<?php

namespace App\Repositories;

use App\Models\Item;
use App\Models\ItemColor;
use App\Models\ItemSize;
use Illuminate\Http\Request;

class ItemRepository
{
    public function rules($page, Request $request){
        $rules = [
            "name_en"           => ['required'],
            "name_ar"           => ['required'],
            "price"             => ['required'],
            "tax"               => ['required'],
            "quantity"          => ['required'],
            "branches"          => ['required'],
            "description_ar"    => ['required'],
            "description_en"    => ['required'],
            "category_id"       => ['required'],
        ];

        if ($page == "create"){
            $rules['item_image'] = ["required", "file", "mimes:jpg,jpeg,png,bmp","max:512"];
            $rules['gallery'] = ["required"];
        }elseif ($page == "edit" && $request->hasFile("item_image")){
            $rules['item_image'] = ["file", "mimes:jpg,jpeg,png,bmp","max:512"];
        }

        return $rules;
    }

    public function save(Request $request){
        $item = new Item();
        $item->name_en = $request->name_en;
        $item->name_ar = $request->name_ar;
        $item->description_en = $request->description_en;
        $item->description_ar = $request->description_ar;
        $item->category_id = $request->category_id;
        $item->price = $request->price;
        $item->quantity = $request->quantity;
        $item->tax = $request->tax;
        $item->save();
        $item->saveMedia($request->file("item_image"), "main_photo");
        foreach ($request->file("gallery") as $file) {
            $item->saveMedia($file, "more_photo");
        }

        foreach ($request->branches as $branch)
            $item->branches()->attach($branch);

        foreach ($request->sizes as $size) {
            $itemSize = new ItemSize();
            $itemSize->size = $size;
            $itemSize->item_id = $item->id;
            $itemSize->save();
        }

        foreach ($request->colors as $index => $color) {
            $itemColor = new ItemColor();
            $itemColor->color = $color;
            $itemColor->item_id = $item->id;
            $itemColor->save();
            if ($request->file("image_color")[$index])
                $itemColor->saveMedia($request->file("image_color")[$index]);
        }
    }

    public function update(Request $request){
        $item = Item::find($request->id);
        $item->name_en = $request->name_en;
        $item->name_ar = $request->name_ar;
        $item->description_en = $request->description_en;
        $item->description_ar = $request->description_ar;
        $item->category_id = $request->category_id;
        $item->price = $request->price;
        $item->quantity = $request->quantity;
        $item->tax = $request->tax;
        $item->save();

        if ($request->hasFile("item_image")){
            if ($item->getFirstMediaFile("main_photo"))
                $item->removeAllGroupFiles("main_photo");
            $item->saveMedia($request->file("item_image"), "main_photo");
        }

        if ($request->hasFile("gallery")){
            if ($item->getFirstMediaFile("more_photo"))
                $item->removeAllGroupFiles("more_photo");
            foreach ($request->file("gallery") as $file) {
                $item->saveMedia($file, "more_photo");
            }
        }

        $branches = [];
        foreach ($request->branches as $branch)
            $branches[] = $branch;
        $item->branches()->sync($branches);

        foreach ($item->sizes as $size)
            $size->delete();

        foreach ($request->sizes as $size) {
            $itemSize = new ItemSize();
            $itemSize->size = $size;
            $itemSize->item_id = $item->id;
            $itemSize->save();
        }

        foreach ($item->colors as $color)
            $color->delete();

        foreach ($request->colors as $color) {
            $itemColor = new ItemColor();
            $itemColor->color = $color;
            $itemColor->item_id = $item->id;
            $itemColor->save();
        }
    }
}