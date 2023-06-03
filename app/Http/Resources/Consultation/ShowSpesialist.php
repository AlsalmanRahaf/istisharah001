<?php

namespace App\Http\Resources\Consultation;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use App\Traits\Helper;
class ShowSpesialist extends JsonResource
{
    use Helper;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "room_id"=>$this->room->room_id,
            "type"=>$this->type == 1 ? "Pharmacist" : ($this->SpesialistConsultant->type == 2 ?  "Pharmacist": "diabetes")
        ];

    }
}
