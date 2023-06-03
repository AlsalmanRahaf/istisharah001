<?php

namespace App\Http\Resources\users;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class NotificationResource extends JsonResource
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
            "id"=>$this->id,
            "title"=>$this->title,
            "body"=>$this->body,
            "created_at"=>$this->created_at->diffForHumans(),
            "status"=>$this->status
        ];

    }
}
