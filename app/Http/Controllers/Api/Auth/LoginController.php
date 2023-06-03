<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\BookingNotificationTime;
use App\Models\Doctor;
use App\Models\Notifications;
use App\Models\ObjectBooking;
use App\Models\ObjectWeekDays;
use App\Models\TimeSlot;
use App\Models\User;
use App\Models\UserBookings;
use App\Models\UserDeviceToken;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Firebase\Auth\Token\Exception\InvalidToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Illuminate\Support\Facades\Auth;
use App\Traits\Notifications as NotificationsTrait;
use App\Traits\Firebase as ff;

class LoginController extends Controller
{
    use NotificationsTrait;
    use ff;
    protected  $repository;
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function rules(){
        return [
            "phone_number"     => ["required", "unique:users"],
            "device_token"     => ["required"],
            "firebase_uid"     =>["required"]
        ];
    }

    public function login(Request $request){
        $auth = Firebase::auth();
        $firebaseToken = $request->firebase_token;
        try {
            $verifiedIdToken = $auth->verifyIdToken($firebaseToken);
            $uid = $verifiedIdToken->claims()->get('sub');
            $user = User::where('firebase_uid',$uid)->first();
            if($user){
                $custom_type=$this->repository->getCustomType($user->id);
                if($user->device_token != $request->device_token){
                    $checkDeviceToken = UserDeviceToken::where("device_token", $user->device_token);
                    if($checkDeviceToken->exists()){
                       $checkUser = $checkDeviceToken->first();
                        if($checkUser->user_id != $user->id){
                            $checkUser->user_id = $user->id;
                            $checkUser->save();
                        }
                    }
                    else{
                        $userDevicesToken = new UserDeviceToken();
                        $userDevicesToken->user_id = $user->id;
                        $userDevicesToken->device_token = $user->device_token;
                        $userDevicesToken->save();
                    }
                    $user->device_token=$request->device_token;
                    $user->save();
                }
                $token = $user->createToken(env("TOKEN_KEY"));
                $tokenObj = $token->token;
                $tokenObj->expires_at = Carbon::now()->addWeeks(4);
                $tokenObj->save();
                $checkDoctor = Doctor::where("user_id", $user->id)->exists();
                $nearestBooking = [];
                $currentDate = date('Y-m-d');
                $userBookings = UserBookings::where("user_id", $user->id)->get("reservation_record_id");
                $reservationRecordArr = [];
                if(count($userBookings) > 0){
                    for ($i=0; $i<count($userBookings); $i++){
                        array_push($reservationRecordArr, $userBookings[$i]->reservation_record_id);
                    }
                    $bookings = ObjectBooking::whereIn("reservation_record_id", $reservationRecordArr)->where("date", ">=", $currentDate)->where("is_cancelled", 0)->orderBy('date')->first();
                    if(!is_null($bookings)){
                        $slot = TimeSlot::where("id", $bookings->slot_id)->first();
                        $day = ObjectWeekDays::where("id", $slot->object_week_days_id)->first();
                        $timeFrom = strtotime($slot->time_from);
                        $timeTo = strtotime($slot->time_to);
                        $newFormatFrom = date('H:i',$timeFrom);
                        $newFormatTo = date('H:i',$timeTo);
                        $bookings["slot_from"] = $newFormatFrom;
                        $bookings["slot_to"] = $newFormatTo;
                        $bookings["day"] = $request->lang == "ar" ? $day->week_day_ar_name : $day->week_day_en_name;
                        $bookings["doctor_name"] = Doctor::where("object_id", $bookings->object_id)->first()->full_name;
                        unset($bookings["created_at"]);
                        unset($bookings["updated_at"]);
                        unset($bookings["slot_id"]);
                        array_push($nearestBooking, $bookings);
                    }
                }
                $data["user"] = [
                    "id" => $user->id,
                    "full_name" => $user->full_name,
                    "user_type" => $user->type,
                    "status" => $user->status,
                    "phone_number" => $user->phone_number,
                    "custom_type"=>$custom_type,
                    "is_doctor" => $checkDoctor,
                    "photo_profile" => $user->getFirstMediaFile("profile_photo") ? $user->getFirstMediaFile("profile_photo")->url : null,
                    "nearest_booking" => $nearestBooking,
                ];

                if($user->type == "u" && $custom_type == null){
                    $data["user"]["switch_status"]='u';
                }else{
                    $data["user"]["switch_status"]=$user->switch_status == 0 ? ($custom_type != null ?"cs":$user->type) : "u";
                }
                $data["token"] = $token->accessToken;
                $this->UserUpdateStatus(1);
                return JsonResponse::data($data)->message("login success")->send();
//                return JsonResponse::error()->message()->changeCode(401)->changeStatusNumber('S401')->send();

            }else{
                $valid = Validator::make($request->all(),  $this->rules());
                if($valid->fails()){
                    return JsonResponse::validationErrors($valid->errors()->messages())->initAjaxRequest()->send();
                }
                $data = $this->repository->register($request);
                return JsonResponse::data($data)->message("created user success")->send();
//                return JsonResponse::error()->message()->changeCode(401)->changeStatusNumber('S401')->send();

            }

        } catch (\InvalidArgumentException $e) {
            return JsonResponse::error()->message($e->getMessage())->changeCode(401)->changeStatusNumber('S401')->send();
        } catch (InvalidToken $e) { // If the token is invalid (expired ...)
            return JsonResponse::error()->message('token not valid')->changeCode(401)->changeStatusNumber('S401')->send();
        }

    }

    public function testLogin(Request $request){
        /*$currentDate= date('Y-m-d');
        $currentTime= date('H:i');
        $timeAfter5Min = Carbon::now()->addMinutes(5)->format('H:i');
        $times = BookingNotificationTime::where([["notification_date", $currentDate], ["is_sent", 0]])->whereBetween('notification_time', [$currentTime, $timeAfter5Min])->get();
        for($i=0; $i<count($times); $i++){
            $booking = ObjectBooking::where("id", $times[$i]->booking_id)->first();
            $slotTime = TimeSlot::where("id", $booking->slot_id)->first();
            $timeFrom = strtotime($slotTime->time_from);
            $timeTo = strtotime($slotTime->time_to);
            $newFormatFrom = date('H:i',$timeFrom);
            $newFormatTo = date('H:i',$timeTo);
            $userId = UserBookings::where("reservation_record_id", $booking->reservation_record_id)->first()->user_id;
            $doctor = Doctor::where("object_id", $booking->object_id)->first();
            $user = User::where("id", $userId)->first();

            $doctorNotificationLang = User::where("id", $doctor->user_id)->first()->notification_lang;
            $userNotificationMsg = $this->getNotificationTextDetails("booking_reminder", ["type"=>$booking->is_online, "date"=>$booking->date, "from"=> $newFormatFrom, "to"=> $newFormatTo, "name"=>$doctor->full_name]);
            $doctorNotificationMsg = $this->getNotificationTextDetails("booking_reminder", ["type"=>$booking->is_online, "date"=>$booking->date, "from"=> $newFormatFrom, "to"=> $newFormatTo, "name"=>$user->full_name]);
            $userNotificationTitle = $userNotificationMsg['title'][$user->notification_lang];
            $userNotificationBody= $userNotificationMsg['body'][$user->notification_lang];
            $doctorNotificationTitle = $doctorNotificationMsg['title'][$doctorNotificationLang];
            $doctorNotificationBody= $doctorNotificationMsg['body'][$doctorNotificationLang];
            $userNotification=new Notifications();
            $userNotification->title= $userNotificationTitle;
            $userNotification->body= $userNotificationBody;
            $userNotification->user_id=$userId;
            $userNotification->type=1;
            $userNotification->status=1;
            $userNotification->save();

            $doctorNotification=new Notifications();
            $doctorNotification->title= $doctorNotificationTitle;
            $doctorNotification->body= $doctorNotificationBody;
            $doctorNotification->user_id=$doctor->user_id;
            $doctorNotification->type=1;
            $doctorNotification->status=1;
            $doctorNotification->save();
            $userDevicetoken = $this->getTokens(User::findMany($userId));
            $doctorDevicetoken = $this->getTokens(User::findMany($doctor->user_id));
            $otherUserDevicesToken = UserDeviceToken::where("user_id", $userId)->get("device_token");
            if(count($otherUserDevicesToken) > 0){
                for($j=0; $j<count($otherUserDevicesToken); $j++){
                    $userDevicetoken[] = $otherUserDevicesToken[$j]->device_token;
                }
            }
            $otherDoctorDevicesToken = UserDeviceToken::where("user_id", $doctor->user_id)->get("device_token");
            if(count($otherDoctorDevicesToken) > 0){
                for($k=0; $k<count($otherDoctorDevicesToken); $k++){
                    $doctorDevicetoken[] = $otherDoctorDevicesToken[$k]->device_token;
                }
            }
            $this->sendFirebaseNotificationCustom(["title"=>$userNotificationTitle,"body"=>$userNotificationBody],$userDevicetoken);
            $userNot = Notifications::find($userNotification->id);
            $userNot->is_sent = 1;
            $userNot->save();
            $this->sendFirebaseNotificationCustom(["title"=>$doctorNotificationTitle,"body"=>$doctorNotificationBody],$doctorDevicetoken);
            $doctorNot = Notifications::find($doctorNotification->id);
            $doctorNot->is_sent = 1;
            $doctorNot->save();
            $notificationTime = BookingNotificationTime::find($times[$i]->id);
            $notificationTime->is_sent = 1;
            $notificationTime->sent_date = Carbon::now()->format('Y-m-d');
            $notificationTime->sent_time = Carbon::now()->format('H:i:s');
            $notificationTime->notification_id = $notificationTime->user_id == $userId ? $userNotification->id : $doctorNotification->id;
            $notificationTime->save();
            dd("****");
        }
        dd("////");*/
        $user = User::find($request->user_id);
        if($user){
            $custom_type=$this->repository->getCustomType($user->id);
            $token = $user->createToken(env("TOKEN_KEY"));
            $tokenObj = $token->token;
            $tokenObj->expires_at = Carbon::now()->addWeeks(4);
            $tokenObj->save();
            $data["token"] = $token->accessToken;
            $data["user"] = [
                "full_name" => $user->full_name,
                "user_type" => $user->type,
                "email" => $user->full_name,
                "phone_number" => $user->phone_number,
                "custom_type"=>$custom_type,
                "photo_profile" => $user->getFirstMediaFile("profile_photo") ? $user->getFirstMediaFile("profile_photo")->url : null,
            ];
            if($user->type == "u" && $custom_type == null){
                $data["user"]["switch_status"]='u';
            }else{
                $data["user"]["switch_status"]=$user->switch_status == 0 ? ($custom_type != null ?"cs":$user->type) : "u";
            }
            return JsonResponse::data($data)->message("login success")->send();
        }else{
            return JsonResponse::error()->message("user not found")->changeCode(401)->changeStatusNumber("S401")->send();
        }
    }


    public function logout(Request $request){
        if (Auth::guard("api")->check()) {
            UserDeviceToken::where([["device_token", $request->device_token], ["user_id", Auth::guard("api")->user()->token()->user_id]])->delete();
            $user = User::where([["id", Auth::guard("api")->user()->token()->user_id], ["device_token", $request->device_token]]);
            if($user->exists()){
                $user = $user->first();
                $user->device_token = null;
                $user->save();
            }
            Auth::guard("api")->user()->token()->revoke();
            $this->UserUpdateStatus(0);
            return JsonResponse::success()->changeCode(200)->message("logout successfully")->send();
        }else
            return JsonResponse::error()->message("the user is not logged in")->changeCode(404)->changeStatusNumber("S404")->send();
    }


    public function UserUpdateStatus($status){
//        $user=User::find(Auth::user()->id);
//        $user->status=$status;
//        $user->save();
    }



}
