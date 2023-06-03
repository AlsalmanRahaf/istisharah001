<?php

namespace App\Http\Resources\users;

use App\Http\Resources\MappingResource;


class UserResource extends MappingResource
{
    public static function classesSupported(): array
    {
        return [
            \App\Http\Resources\users\ShowUserResource::class,
            \App\Http\Resources\users\promoCodeResource::class,
            \App\Http\Resources\users\NotificationResource::class,
            \App\Http\Resources\users\BlockedUsersResource::class,

        ];
    }

}
