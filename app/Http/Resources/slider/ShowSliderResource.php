<?php

namespace App\Http\Resources\slider;

use Illuminate\Http\Resources\Json\JsonResource;

class ShowSliderResource extends JsonResource
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
            "status" => $this->full_name,
            "slider_photo" => $this->getFirstMediaFile('slider_photo')->url ?? null

        ];

    }
}
