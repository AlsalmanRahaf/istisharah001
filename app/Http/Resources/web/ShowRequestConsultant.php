<?php

namespace App\Http\Resources\web;

use Illuminate\Http\Resources\Json\JsonResource;

class ShowRequestConsultant extends JsonResource
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
            "id" => $this->id,
            "user_name" => $this->users->full_name,

        ];
    }
}
