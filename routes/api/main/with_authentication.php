<?php

//Profiles Routes

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\users\BookingController;
use App\Http\Controllers\Api\SpecializationController;
use App\Http\Controllers\Api\ConsultantController;


Route::prefix("profile")->group(function (){
    Route::get("/", "ProfileController@show");
    Route::post("/", "ProfileController@update");
});

//Route::post("usersusers", [\App\Http\Controllers\Web\Admin\DateController::class, ])
Route::prefix("users")->group(function (){
    Route::post("/show_all_user", "users\UsersController@index");
    Route::get("/show", "users\UsersController@show");
    Route::get("/hasConsultantChat", "users\UsersController@hasConsultantChat");
    Route::post("/update", "users\UsersController@update");
    Route::post("/ChangeUser", "users\UsersController@ChangeUser");
    Route::get("/showPromocodeData", "users\UsersController@show_promocode_data");
    Route::post("checktoken","users\UsersController@checktoken");
    Route::post("/block_users", "users\BlockedUsersController@store");
    Route::get("/blocked_list", "users\BlockedUsersController@index");
    Route::post("/delete-user", "users\UsersController@delete");
    Route::put("/change-notification-lang", "users\UsersController@changeNotificationLang");
});

Route::prefix("sliders")->group(function (){
    Route::get("/show", "slider\SliderController@index");
    Route::post("/create", "slider\SliderController@create");
});
Route::prefix("consultant")->group(function (){
    Route::get("/info/{ConsultantID}", "ConsultantInfoController@index");
});

Route::prefix("room")->group(function (){
    Route::post("/create", "chat\CreateRoomController@createRoom");
    Route::post("/message_status", "chat\CreateRoomController@message_status");
    Route::get("/getRoomChat", "chat\CreateRoomController@showRoomChat");
    Route::post("/createConsultant", "chat\ConsultantController@createConsultant");
    Route::post("/UpdateConsultant", "chat\ConsultantController@UpdateConsultant");
    Route::get("/get_Room_request_consultant", "chat\CreateRoomController@get_room_request_counsaltant");
});

Route::prefix("Consultant")->group(function () {

    Route::post("/RequestConsultant", "chat\ConsultantController@RequestConsultant");
    Route::get("/getRequestListConsultant", "chat\ConsultantController@RequestListConsultant");
    Route::get("/getUnReadConsultation", "chat\ConsultantController@getUnReadConsultation");

    Route::get("/checkConsultantRequest", "chat\ConsultantController@checkConsultantRequest");
    Route::get("/getConsultationByStatus", "chat\ConsultantController@getConsultationByStatus");
    Route::get("/getReConsultationByStatus", "chat\ConsultantController@getReConsultationByStatus");
    Route::get("/getAllConsultant", "chat\ConsultantController@getAllConsultant");
    Route::post("/change_consultant_status", "chat\ConsultantController@change_consultant_status");
    Route::post("/create_consultant_admin_room", "chat\ConsultantAdminController@create_consultant_admin_room");
    Route::post("/block_consultant_consultation", "chat\ConsultantAdminController@block_consultant_consultation");
    Route::get("/check_block_consultant", "chat\ConsultantController@check_block_consultant");
    Route::get("/getSpesialistConsultant", "chat\ConsultantController@getSpesialistConsultant");
    Route::get("/get_consultation_by_userid", "chat\ConsultantController@get_consultation_by_userid");
});

Route::prefix("Consultation")->group(function () {
    Route::get("/getConsultationLocation", "chat\ConsultationLocationController@index");
    Route::get("/checkUserConsultation", "chat\CreateRoomController@checkUserConsultation");
    //custom consultation
    Route::get("/get_custom_consultation_by_status", "chat\CustomConsultationController@get_custom_consultation_by_Status");
    Route::get("/check_custom_user_consultation", "chat\CustomConsultationController@checkUserConsultation");
    Route::get("/get_custom_Consultation", "chat\CustomConsultationController@index");
});



Route::prefix("HistoryChat")->group(function () {
    Route::get("/getConsultationChatHistory", "chat\HistoryChatController@getConsultationChatHistory");
    Route::get("/getConsultationChatHistoryById", "chat\HistoryChatController@getConsultationChatHistoryById");
    Route::post("/deleteChat", "chat\HistoryChatController@deleteChat");
});


Route::prefix("Message")->group(function () {
    Route::get("/WelcomeMessage", "welcomeMessage\WelcomeMessage@index");
});

Route::resource("WelcomeMessage", "WelcomeMessageController");




Route::prefix("media")->group(function (){
    Route::post("/uploadMedia", "chat\CreateRoomController@uploadMedia");
});
Route::prefix("PromoCode")->group(function (){
    Route::post("/createPromoCode", "promoCode\PromoCodeController@createPromoCode");
    Route::post("/UsesPromoCode", "promoCode\PromoCodeController@UsesPromoCode");
});


Route::prefix("Ads")->group(function (){
    Route::get("/show", "ads\AdsController@index");
    Route::get("/create_support_ads", "ads\AdsController@create_support_ads");
    Route::post("/create", "ads\AdsController@create");
    Route::get("/get_support_ads", "ads\AdsController@get_support_ads_list");
    Route::get("/sendnotseen", "ads\AdsController@sendnotseen");
});


Route::prefix("notification")->group(function (){
    Route::get("/show", "users\NotificationController@show");
    Route::get("/update", "users\NotificationController@update");

});


Route::prefix("SocialMedia")->group(function (){
    Route::get("/show", "socialmedia\SocialMediaController@index");
    Route::post("/create", "socialmedia\SocialMediaController@create");
});

Route::prefix("AppSetting")->group(function (){
    Route::get("/show", "appSetting\AppSettingController@index");
    Route::get("/check", "appSetting\AppSettingController@check");
    Route::get("/getMainButtons", "appSetting\AppSettingController@getMainButtons");

});

Route::get('/get-all-specialization' , [SpecializationController::class, 'getAllSpecialization']);

// Doctor APIs
Route::prefix("consultant")->group(function (){
    Route::get('/get-all-consultants-by-specialization/{specialization_id}' , [ConsultantController::class, 'getAllConsultantsBySpecialization']);
    Route::post('/get-consultant-time-slots' , [ConsultantController::class, 'getConsultantTimeSlots']);
});

Route::prefix("SaveMessageMedia")->group(function () {
    Route::post("/", function (Request $request) {
        return response()->json($request[0]->files, 200);
    });
});

Route::prefix("user-bookings")->group(function (){
    Route::get("/{status}", [BookingController::class, "getUserBookings"]);
    Route::post('/add' , [BookingController::class, 'addBooking']);
    Route::get("/get-booking-details/{id}", [BookingController::class, "getBookingDetails"]);
    Route::post('/cancel-booking' , [BookingController::class, 'cancelBooking']);
    Route::post('/add-booking-rating' , [BookingController::class, 'addBookingRating']);
});

Route::prefix("consultant-bookings")->group(function (){
    Route::get("/{status}", [BookingController::class, "getConsultantBookings"]);
    Route::get("/get-booking-details/{id}", [BookingController::class, "getConsultantBookingDetails"]);
});

