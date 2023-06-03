<?php

namespace App\Http\Resources\web;

use Illuminate\Http\Resources\Json\JsonResource;

class MessagesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $room_member=[];
        foreach ($this->room_members as $member){
            $room_member[]= $member->users;
        }
        return [
            "id" => $this->id,
            "status"=>$this->status,
            "type"=>$this->type,
            "standard"=>$this->standard,
            "chat_type"=>$this->chat_type,
            "created_at"=>$this->created_at,
            "room"=>$room_member
        ];
    }
}
