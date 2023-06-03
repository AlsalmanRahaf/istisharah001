<?php

namespace App\Models;

use App\Helpers\Media\Src\IMedia;
use App\Helpers\Media\Src\MediaGroups;
use App\Helpers\Media\Src\MediaInitialization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Item extends Model implements IMedia
{
    use HasFactory, MediaInitialization;

    const IMAGE_PATH = "Items";
    public function setMainDirectoryPath(): string
    {
        return self::IMAGE_PATH;
    }


    public function setGroups(): MediaGroups
    {
        return (new MediaGroups())->setGroup("single", "main_photo", DS . "main_photo")
            ->setGroup("multi", "more_photo", DS ."more_photo");
    }

    public function getNameAttribute(){
        return $this->{"name_" . App::getLocale()};
    }

    public function getDescriptionAttribute(){
        return $this->{"description_" . App::getLocale()};
    }

    public function branches(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Branch::class, "item_branches", "item_id", "branch_id");
    }

    public function category(){
        return $this->belongsTo(Category::class, "category_id");
    }

    public function sizes(){
        return $this->hasMany(ItemSize::class, "item_id");
    }

    public function colors(){
        return $this->hasMany(ItemColor::class, "item_id");
    }
}
