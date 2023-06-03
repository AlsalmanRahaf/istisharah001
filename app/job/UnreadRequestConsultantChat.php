<?php

namespace App\job;


use App\Http\Controllers\Api\chat\ConsultantAdminController;
use App\Models\Consultant_admin_chat;
use App\Models\RequestConsultantChat;
use App\Traits\Firebase;
use App\Traits\Helper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class UnreadRequestConsultantChat implements ShouldQueue
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
        $Request_consultant_chats=RequestConsultantChat::all();
        if($Request_consultant_chats){
            foreach ($Request_consultant_chats as $Request_consultant_chat){
                if($Request_consultant_chat->room != null && $Request_consultant_chat->room->user_chat != null){
                    $user_chats =$Request_consultant_chat->room->user_chat;
                    foreach ($user_chats as $chat){
                        if($chat->status == 1){
                            $type =$chat->users->type;
                            var_dump($type);
                            $room_members=$chat->room->room_members;
                            foreach ($room_members as $member){
                                if($member->user_id  == $chat->user_id && $type == "u"){
                                    $user_id=$chat->user_id;
                                }else{
                                    $admin_id=$chat->user_id;
                                }
                            }
                            $url=env("NODEJSURL").'/unread_Request_Consultant_chat';
                            $this->sendRequest('post',[
                                'user_id' => $user_id,
                                'admin_id' => $admin_id,
                                'type'=>$type,
                                'status' => true
                            ],$url);
                            var_dump($chat->user_id);
                        }elseif($chat->status == 2){
                            $url=env("NODEJSURL").'/unread_Request_Consultant_chat';
                            $this->sendRequest('post',[
                                'user_id' => $chat->user_id,
                                'admin_id' => $admin_id,
                                'type'=>$type,
                                'status' => false
                            ],$url);
                        }
                    }
                }
            }
        }
    }
}
