<?php

namespace App\Http\Resources\ads;

use Illuminate\Http\Resources\Json\JsonResource;

class LoadingAdsResource extends JsonResource
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
            "image" => $this->getFirstMediaFile('LoadingAds') != null ?  $this->getFirstMediaFile('LoadingAds')->url :""
        ];

    }
}
