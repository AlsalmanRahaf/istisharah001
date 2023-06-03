<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class BranchResource extends JsonResource
{
    use ResourcesHelper;
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
            "name" => $this->store_name,
            "lat" => $this->latitude,
            "lng" => $this->longitude,
            "address" => $this->address,
            "phone_number" => $this->phone_number,
            "category" => $this->category->name,
            "image" => $this->getFirstMediaFile() ? $this->getFirstMediaFile()->url : null,
        ];
    }
}
