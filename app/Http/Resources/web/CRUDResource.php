<?php

namespace App\Http\Resources\web;

use App\Http\Resources\MappingResource;


class CRUDResource extends MappingResource
{
    public static function classesSupported(): array
    {
        return [
            \App\Http\Resources\web\ShowRequestConsultant::class,
        ];
    }

}
