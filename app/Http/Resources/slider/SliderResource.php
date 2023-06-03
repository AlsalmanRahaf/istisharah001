<?php

namespace App\Http\Resources\slider;

use App\Http\Resources\MappingResource;


class SliderResource extends MappingResource
{
    public static function classesSupported(): array
    {
        return [
            \App\Http\Resources\slider\ShowSliderResource::class
        ];
    }

}
