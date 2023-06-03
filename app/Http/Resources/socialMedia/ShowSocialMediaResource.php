<?php


namespace App\Http\Resources\socialMedia;

use Illuminate\Http\Resources\Json\JsonResource;

class ShowSocialMediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "type" => $this->type,
            "url" => $this->url,
            "social_media_photo" => $this->getFirstMediaFile('SocialMedia') ? $this->getFirstMediaFile('SocialMedia')->url :  null

        ];

    }
}
