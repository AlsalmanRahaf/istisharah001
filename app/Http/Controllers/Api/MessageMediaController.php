<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\MediaType;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageMediaController extends Controller
{
    public function index(Request $request){
        $message_id = $request->message_id;
        $media_type=$request->media_type;
        $message=Message::find($message_id);
        $urls=[];
        if($request->hasFile("image")){
            $media=$request->file("image");
            if(is_array($media)){
                for ($i=0;$i<count($media) ;$i++){
                    $NewMedia=$message->savemedia($media[$i],"Message");

                    $mediaT=new MediaType;
                    $mediaT->media_id=$NewMedia->id;
                    $mediaT->media_type=$media_type[$i];
                    $mediaT->save();

                }
            }else{
                $message->savemedia($media,"Message");


            }

        }

        foreach ($message->getFirstMediaFile("Message") as $file){
            $urls[]=$file->url;
        }
        return $urls;

    }
}
