<?php

namespace App\Models;

use App\Helpers\Media\Src\IMedia;
use App\Helpers\Media\Src\MediaGroups;
use App\Helpers\Media\Src\MediaInitialization;
use Illuminate\Database\Eloquent\Model;

class SliderMarket extends Model implements IMedia
{
    use MediaInitialization;
    protected $fillable = [
        "type", "navigate_id", "Status", "created_at", "updated_at", "branch_id"
    ];
    protected $table="sliders_market";

    protected $appends = ["navigator_name_en", "navigator_name_ar"];


    public function navigator(){
        $navigate = $this->type == 1 ? Category::class : Item::class;
        return $this->hasOne($navigate, 'id','navigate_id');
    }

    public function getNavigatorNameEnAttribute(){
       return  $this->navigator()->exists() ?
           ($this->type == 1 ? $this->navigator->name_en : $this->navigator->name_en)
           : null;
    }
    public function getNavigatorNameArAttribute(){
        return  $this->navigator()->exists() ?
            ($this->type == 1 ? $this->navigator->name_ar : $this->navigator->name_ar)
            : null;
    }

    const IMAGE_PATH = "slider_market";
    public function setMainDirectoryPath(): string
    {
        return self::IMAGE_PATH;
    }


    public function setGroups(): MediaGroups
    {
        return (new MediaGroups())->setGroup("single", "slider_market_photo", DS);
    }

}
