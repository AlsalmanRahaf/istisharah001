<?php

namespace App\Http\Resources\ads;


use Illuminate\Http\Resources\Json\JsonResource;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class ShowAdsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Ilaluminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data=[];
        if($this->type == 1)
            $data["text"]= $this->Ads_text;


        foreach ($this->getFirstMediaFile('Ads') as $file){
            $data[]=$file->url;
        }


        return [
            "id" => $this->id,
            "type" => $this->type == 4 ?3:$this->type,
            "data" => $data,
            "logo" =>$this->getFirstMediaFile('Logo') != null ? $this->getFirstMediaFile('Logo')->url : "",
        ];

    }
}
