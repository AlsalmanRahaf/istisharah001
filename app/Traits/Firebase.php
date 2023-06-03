<?php
namespace App\Traits;
use Illuminate\Support\Facades\Session;

trait Firebase{
    use ApiResponse;

    public function sendFirebaseNotification($notification, $token){
            $data = [
                "registration_ids" => $token,
                "notification" => [
                    "title" => [
                        "en" => $notification["title"]["en"],
                        "ar" => $notification["title"]["ar"]
                    ],
                    "body" => [
                        "en" => $notification["body"]["en"],
                        "ar" => $notification["body"]["ar"],
                        "secound" => 10
                    ],
                ]
            ];
          $this->send($data);
        }


    public function sendFirebaseNotificationCustom($notification, $token){

        if(is_array($token)){
            $data["registration_ids"]=$token;
        }else{
            $data["to"]="/topics/".$token;
        }
     //   $data["to"]=$token;
        $data["notification"] =
            [
                "title" => $notification["title"],
                "body" => $notification["body"]
            ];

      return $this->sendNotification($data);

    }

        public function sendNotification($data){

        $SERVER_API_KEY = env("FIREBASE_API_KEY");
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];
            try {
            $ch = curl_init();
            $jsonData = json_encode($data);
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

            $response = curl_exec($ch);
            return $response;

        } catch (\Exception $e){
            return $this->sendError("error firebase", 401,401);
        }
    }

    public function getTokens($users){
        $data=[];
        foreach ($users as $user){
            $data[]=$user->device_token;
        }
        return $data;
    }

    public function sendNotificationNew($notification, $token){
        try {
            $SERVER_API_KEY = env("FIREBASE_API_KEY");
            $data = [
                "registration_ids" => $token,
                "notification" => [
                    "title" => [
                        "en" => $notification["title"]["en"],
                        "ar" => $notification["title"]["ar"]
                    ],
                    "body" => [
                        "en" => $notification["body"]["en"],
                        "ar" => $notification["body"]["ar"]
                    ],
                ]
            ];
            $headers = [
                'Authorization: key=' . $SERVER_API_KEY,
                'Content-Type: application/json',
            ];

            $ch = curl_init();
            $jsonData = json_encode($data);

            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

            $response = curl_exec($ch);

        } catch (\Exception $e){
            return $this->sendError("error firebase", 401,401);
        }
    }

}
