<?php

namespace App\Http\Resources\Consultation;

use App\Http\Resources\MappingResource;


class ConsultationResources extends MappingResource
{
    public static function classesSupported(): array
    {
        return [
            \App\Http\Resources\Consultation\showConsultationResource::class,
            \App\Http\Resources\Consultation\ShowCustomConsultationResource::class
        ];
    }

}
