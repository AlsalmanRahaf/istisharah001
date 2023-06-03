<?php

namespace App\Http\Resources\Consultation;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ReferredConsultationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [
            "room_id" => $this->room->room_id,
            "created_at" => $this->created_at->toDateString()
        ];


        if (isset($this->type)) {
            switch ($this->type) {
                case 1 :
                    $name = "Pharmacist";
                    break;
                case 2 :
                    $name = "Nutrition";
                    break;
                case 3 :
                    $name = "Diabetes";
                    break;
            }
            $data["type"] = $this->type;
            $data["name"] = $name;
        }
        return $data;

    }
}
