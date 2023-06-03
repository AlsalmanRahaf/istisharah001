<?php

namespace App\Http\Resources\chat;

use App\Http\Resources\MappingResource;


class chatResource extends MappingResource
{
    public static function classesSupported(): array
    {
        return [
            \App\Http\Resources\chat\showChatResource::class,
            \App\Http\Resources\chat\ConsultationChatHistory::class,
            \App\Http\Resources\chat\ConsultationLocationResource::class,
            \App\Http\Resources\chat\ButtonResource::class
        ];

    }

}
