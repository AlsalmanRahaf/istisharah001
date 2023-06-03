<?php


namespace App\Http\Resources\socialMedia;

use App\Http\Resources\MappingResource;


class SocialResource extends MappingResource
{
    public static function classesSupported(): array
    {
        return [
            \App\Http\Resources\socialMedia\ShowSocialMediaResource::class,
        ];
    }

}
