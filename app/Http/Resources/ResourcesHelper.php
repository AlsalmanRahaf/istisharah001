<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\App;

trait ResourcesHelper
{

    protected static $attributes;


    public function append(Array $attr){
        $this->attributesForClass = $attr;
        return $this;
    }


}
