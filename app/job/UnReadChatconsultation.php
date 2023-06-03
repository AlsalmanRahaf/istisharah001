<?php

namespace App\job;


use App\Models\consultation;
use App\Models\ReferredConsultation;
use App\Models\UserCustomConsultation;
use App\Traits\Firebase;
use App\Traits\Helper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class UnReadChatconsultation implements ShouldQueue
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
       $consultations= consultation::where("consultations_status",2)->get();
       $ReferredConsultation= ReferredConsultation::where("status",2)->get();
       $UserCustomConsultation= UserCustomConsultation::where("consultation_status",2)->get();
        if($consultations){
                    $staus=$this->getChatStatus($consultations);
                    if($staus){
                        var_dump("done");
                        $url=env("NODEJSURL").'/getUnReadConsultation';
                        $this->sendRequest('post',[
                            'type' => 'c',
                            'status' => true
                        ],$url);
                    }else{
                        $url=env("NODEJSURL").'/getUnReadConsultation';
                        $this->sendRequest('post',[
                            'type' => 'c',
                            'status' => false
                        ],$url);
                    }
        }
        if($ReferredConsultation){
            $staus=$this->getChatStatus($ReferredConsultation);
            if($staus){
                var_dump("done2");
                $url=env("NODEJSURL").'/getUnReadConsultation';
                $this->sendRequest('post',[
                    'type' => 'sp',
                    'status' => true
                ],$url);
            }else{
                $url=env("NODEJSURL").'/getUnReadConsultation';
                $this->sendRequest('post',[
                    'type' => 'sp',
                    'status' => false
                ],$url);
            }
        }
        if($UserCustomConsultation){
            $staus=$this->getChatStatus($UserCustomConsultation);
            if($staus){
                var_dump("done3");
                $url=env("NODEJSURL").'/getUnReadConsultation';
                $this->sendRequest('post',[
                    'type' => 'cs',
                    'status' => true
                ],$url);
            }else{
                $url=env("NODEJSURL").'/getUnReadConsultation';
                $this->sendRequest('post',[
                    'type' => 'cs',
                    'status' => false
                ],$url);
            }
        }
    }


    public function getChatStatus($consultations){
        $status=false;
        foreach ($consultations as $consultation){
            if($consultation->room != null && $consultation->room->user_chat != null){
                $user_chats =$consultation->room->user_chat;
                foreach ($user_chats as $chat){
                    if($chat->status == 1 && $chat->users->type == "u"){
                        return true;
                    }
                }
            }
        }
        return $status;
    }



}
