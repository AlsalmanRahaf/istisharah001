<?php

namespace App\job;


use App\Http\Controllers\Api\chat\ConsultantAdminController;
use App\Models\Consultant_admin_chat;
use App\Traits\Firebase;
use App\Traits\Helper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class UnReadChatConsultationAdmin implements ShouldQueue
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
        $chat_admin_consultants=Consultant_admin_chat::all();
        if($chat_admin_consultants){
            foreach ($chat_admin_consultants as $chat_admin_consultant){
                if($chat_admin_consultant->room != null && $chat_admin_consultant->room->user_chat != null){
                    $user_chats =$chat_admin_consultant->room->user_chat;
                    foreach ($user_chats as $chat){
                        $type =$chat->users->type;
                         $room_members=$chat->room->room_members;
                         foreach ($room_members as $member){

                             if( $member->users->type == "a"){
                                 $admin_id=$member->users->id;
                             }elseif($member->users->type == "c"){
                                 $consultant_id=$member->users->id;
                             }
                         }

                        if($chat->status == 1){
                            $url=env("NODEJSURL").'/unread_Admin_Consultant_chat';
                            $this->sendRequest('post',[
                                'consultant_id' => $consultant_id,
                                'admin_id'=>$admin_id,
                                'type'=>$type,
                                'status' => true
                            ],$url);

                        } elseif($chat->status == 2){
                            $url=env("NODEJSURL").'/unread_Admin_Consultant_chat';
                            $this->sendRequest('post',[
                                'consultant_id' => $consultant_id,
                                'admin_id'=>$admin_id,
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
