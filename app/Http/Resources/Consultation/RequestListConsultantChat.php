<?php

namespace App\Http\Resources\Consultation;

use Illuminate\Http\Resources\Json\JsonResource;


class RequestListConsultantChat extends JsonResource
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
            "full_name" =>  $this->RequestConsultant->users->full_name,
            "room_id" =>  $this->room->room_id,
            "phone_number" =>$this->RequestConsultant->users->phone_number,
            "image" => $this->RequestConsultant->users->getFirstMediaFile('profile_photo')->url ?? "",
        ];
    }
}
