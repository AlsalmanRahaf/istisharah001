<?php

namespace App\Http\Resources\users;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class promoCodeResource extends JsonResource
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
            "points"=>$this->points,
            "Promo_code"=>$this->Promo_code,
            "users_used"=>$this->getUsersUsed($this->UsesPromoCode)

        ];

    }



    public function getUsersUsed($UsesPromoCodes){
        $data=[];
        foreach ($UsesPromoCodes as $UsesPromoCode){
            $user=$UsesPromoCode->users;
            $data[]= [
                "full_name"=>$user->full_name,
                "image"=>$user->getFirstMediaFile('profile_photo')->url ?? null,
                "used_at"=>$UsesPromoCode->created_at];
        }
        return $data;
    }
}
