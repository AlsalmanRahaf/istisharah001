<?php

namespace App\Http\Resources\ads;

use Illuminate\Http\Resources\Json\JsonResource;

class SupportAdsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Ilaluminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "room_id" => $this->room->room_id,
            "user_name" =>$this->user->full_name,
            "user_image" =>$this->user->getFirstMediaFile('profile_photo') != null ? $this->user->getFirstMediaFile('profile_photo')->url : "",
            "phone_number" =>$this->user->phone_number
        ];

    }
}
