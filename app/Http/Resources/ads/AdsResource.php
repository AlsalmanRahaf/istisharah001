<?php

namespace App\Http\Resources\ads;

use App\Http\Resources\MappingResource;


class AdsResource extends MappingResource
{
    public static function classesSupported(): array
    {
        return [
            \App\Http\Resources\ads\ShowAdsResource::class,
            \App\Http\Resources\ads\SupportAdsResource::class
        ];
    }

}
