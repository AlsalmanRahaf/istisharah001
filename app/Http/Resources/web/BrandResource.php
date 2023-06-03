<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class CategoryResource extends JsonResource
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
            "name" => $this->name,
            "description" => $this->name,
            "category" => $this->category->name,
            "image" => $this->getFirstMediaFile() ? $this->getFirstMediaFile()->url : null,
        ];
    }
}
