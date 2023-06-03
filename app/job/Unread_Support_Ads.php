<?php

namespace App\job;


use App\Http\Controllers\Api\chat\ConsultantAdminController;
use App\Models\Consultant_admin_chat;
use App\Models\RequestConsultantChat;
use App\Models\SupportAds;
use App\Traits\Firebase;
use App\Traits\Helper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class Unread_Support_Ads implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels,Firebase,Helper;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $Supports_cahts=SupportAds::all();
        if($Supports_cahts){
            foreach ($Supports_cahts as $Supports_caht){
                if($Supports_caht->room != null && $Supports_caht->room->user_chat != null){
                    $user_chats =$Supports_caht->room->user_chat;
                    foreach ($user_chats as $chat){
                        $room_members=$chat->room->room_members;
                        $type =$chat->users->type;
                        foreach ($room_members as $member){
                            if($member->users->type == "u"){
                                $consultant_id=$member->users->id;
                            }else{
                                $admin_id=$member->users->id;
                            }
                        }
                        if($chat->status == 1){
                            $url=env("NODEJSURL").'/unread_support_ads_chat';
                            $this->sendRequest('post',[
                                'user_id' => $consultant_id,
                                'admin_id'=>$admin_id,
                                'type' => $type,
                                'status' => true
                            ],$url);
                            var_dump($url);
                        }elseif($chat->status == 2){
                            $url=env("NODEJSURL").'/unread_support_ads_chat';
                            $this->sendRequest('post',[
                                'user_id' => $consultant_id,
                                'admin_id'=>$admin_id,
                                'type' => $type,
                                'status' => false
                            ],$url);
                        }
                    }
                }
            }
        }
    }
}
