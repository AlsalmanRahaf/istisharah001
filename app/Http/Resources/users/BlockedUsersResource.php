<?php

namespace App\Http\Resources\users;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class BlockedUsersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            "id"=>$this->blocked_users->id,
            "phone_number"=>$this->blocked_users->phone_number
        ];

    }
}
