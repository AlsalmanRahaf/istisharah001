<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Admin\SpecializationController;
use App\Http\Controllers\Web\Admin\DoctorController;
use App\Http\Controllers\Web\Admin\BookingNotificationReminderController;

Route::get("/","DashboardController@index")->name("dashboard.index");


Route::prefix("roles")->name("roles.")->group(function (){
    Route::get("/index", "RoleController@index")->name("index");
    Route::get("/create", "RoleController@create")->name("create");
    Route::post("/store", "RoleController@store")->name("store");
    Route::get("/create/permissions", "RoleController@CreatePermissions")->name("create.permissions");
    Route::post("/store/permissions", "RoleController@StorePermissions")->name("store.permissions");
    Route::post("/update/{id}", "RoleController@update")->name("update");
    Route::get("/edit/{id}", "RoleController@edit")->name("edit");
    Route::delete("destroy/{id}", "RoleController@destroy")->name("destroy");
});


Route::prefix("admins")->name("admins.")->group(function (){
    Route::get("/index", "UsersDashboardController@index")->name("index");
    Route::get("/create", "UsersDashboardController@create")->name("create");
    Route::post("/store", "UsersDashboardController@store")->name("store");
    Route::post("/update/{id}", "UsersDashboardController@update")->name("update");
    Route::get("/edit/{id}", "UsersDashboardController@edit")->name("edit");
    Route::get("/show/{id}", "UsersDashboardController@show")->name("show");
    Route::delete("destroy/{id}", "UsersDashboardController@destroy")->name("destroy");
    Route::get("profile", "UsersDashboardController@profile")->name("profile");
    Route::get("Firewall", "FireWallController@index")->name("Firewall");
    Route::post("check", "FireWallController@check_admin")->name("check");
    Route::post("profile", "UsersDashboardController@saveProfile")->name("profile.save");
});

//Route::resource("admins", "UsersDashboardController");


Route::prefix("sliders")->name("sliders.")->group(function (){
    Route::get("/index", "SlidersController@index")->name("index");
    Route::get("/create", "SlidersController@create")->name("create");
    Route::post("/", "SlidersController@store")->name("store");
    Route::post("/update/{id}", "SlidersController@update")->name("update");
    Route::get("/edit/{id}", "SlidersController@edit")->name("edit");
    Route::post("/show/{id}", "SlidersController@show")->name("show");
    Route::get("/cancel", "SlidersController@cancel")->name("cancel");
    Route::delete("/{id}", "SlidersController@destroy")->name("destroy");
});

Route::prefix("users")->name("users.")->group(function (){

    Route::get("/index", "UsersController@index")->name("index");
    Route::put("/update/{user_id}", "UsersController@update")->name("update");

//    Route::get("/create", "SlidersController@create")->name("create");
//    Route::post("/", "SlidersController@store")->name("store");

//    Route::get("/edit/{id}", "SlidersController@edit")->name("edit");
//    Route::post("/show/{id}", "SlidersController@show")->name("show");
//    Route::post("/destroy", "SlidersController@destroy")->name("destroy");

    // Consultant
    Route::get("/ShowRequestConsultant", "UsersController@ShowRequestConsultant")->name("RequestConsultant");
    Route::put("/UpdateRequestConsultant", "UsersController@UpdateRequestConsultant")->name("UpdateRequestConsultant");
    Route::Delete("/DeleteRequestConsultant", "UsersController@DeleteRequestConsultant")->name("DeleteRequestConsultant");

});

Route::prefix("messages")->name("messages.")->group(function (){
    Route::get("/admin", "MessagesController@admin")->name("admin");
    Route::get("/consultant", "MessagesController@consultant")->name("consultant");
    Route::get("/specializedconsultant", "MessagesController@specializedconsultant")->name("specializedconsultant");
    Route::get("/users", "MessagesController@users")->name("users");
    Route::get("/Other", "MessagesController@getCustomConsultationData")->name("Other");

});


Route::prefix("Ads")->name("Ads.")->group(function (){

    Route::get("/index", "AdvertisementController@index")->name("index");
    Route::get("/loadingAds", "AdvertisementController@LodingAds")->name("loadingAds");

    Route::get("/create", "AdvertisementController@create")->name("create");
    Route::post("/store", "AdvertisementController@store")->name("store");
    Route::delete("/destroy", "AdvertisementController@destroy")->name("destroy");
    Route::get("/edit/{id}", "AdvertisementController@edit")->name("edit");
    Route::put("/update/{id}", "AdvertisementController@update")->name("update");

    Route::get("/create-loading-Ads", "AdvertisementController@createloadingAds")->name("create-loading-ads");
    Route::post("/loadingAds", "AdvertisementController@storeLoadingAds")->name("storeLoadingAds");
    Route::get("/Edit-loading-ads/{id}", "AdvertisementController@EditLoadingAds")->name("Edit-loading-ads");
    Route::put("/updateLoadingAds/{id}", "AdvertisementController@updateLoadingAds")->name("update-ads-loading");
    Route::delete("/loading-ads-destroy", "AdvertisementController@loadingAdsdestroy")->name("loading-ads-destroy");


});


Route::prefix("social-media")->name("social-media.")->group(function (){
    Route::get("/index", "SocialMediaController@index")->name("index");
    Route::get("/create", "SocialMediaController@create")->name("create");
    Route::post("/store", "SocialMediaController@store")->name("store");
    Route::get("/edit/{id}", "SocialMediaController@edit")->name("edit");
    Route::post("/update/{id}", "SocialMediaController@update")->name("update");
    Route::delete("/destroy", "SocialMediaController@destroy")->name("destroy");
    Route::get("/cancel", "SocialMediaController@cancel")->name("cancel");

});
Route::prefix("timeSlotType")->name("timeSlotType.")->group(function (){
    Route::get("/", "TimeSlotTypeController@index")->name("index");
    Route::get("/details/{id}", "TimeSlotTypeController@details")->name("details");

});

Route::prefix("Dates")->name("date.")->group(function (){
    Route::get("/index/{statusId?}/{bookingId?}", "DateController@index")->name("index");
    Route::get("/create", "DateController@create")->name("create");
    Route::get("/details/{id?}/{object_id?}/{reservation_record_id?}", "DateController@details")->name("details");
    Route::get("/edit/{reservation_record_id?}/{user_id?}", "DateController@edit")->name("edit");
    Route::post("/details/update/{reservation_record_id?}", "DateController@update")->name("update");
});

Route::prefix("promo-code")->name("promo-code.")->group(function () {
    Route::get("/index", "PromoCodeController@index")->name("index");
    Route::get("/edit", "PromoCodeController@edit")->name("edit");
    Route::post("/update", "PromoCodeController@update")->name("update");
    Route::delete("/destroy", "PromoCodeController@destroy")->name("destroy");
});

Route::prefix("App-Setting")->name("App-Setting.")->group(function () {
    Route::get("/Setting", "AppSettingController@Setting")->name("Setting");
    Route::get("/create", "AppSettingController@create")->name("create");
    Route::post("/store", "AppSettingController@store")->name("store");
    Route::get("/edit", "AppSettingController@edit")->name("edit");
    Route::put("/update", "AppSettingController@update")->name("update");
    Route::get("/destroy/{id}", "AppSettingController@destroy")->name("destroy");
    Route::get("/ShowMainButton", "AppSettingController@ShowMainButton")->name("showMainButton");
    Route::get("/ShowButtonChatHistory", "AppSettingController@ButtonChatHistory")->name("ButtonChatHistory");
    Route::get("/ConsultationHistory", "AppSettingController@ConsultationHistory")->name("ConsultationHistory");

    Route::put("/UpdateButton", "AppSettingController@UpdateButton")->name("UpdateButton");

});


Route::prefix("consultations")->name("consultations.")->group(function () {
    Route::get("/index", "ConsultationsController@index")->name("index");
    Route::get("/Custom", "CustomConsultationController@index")->name("Custom");
    Route::get("/Custom/Create", "CustomConsultationController@Create")->name("Create");
    Route::post("/Custom/store", "CustomConsultationController@store")->name("store");
    Route::delete("/Custom/destroy", "CustomConsultationController@destroy")->name("destroy");
});


Route::prefix("profile")->name("profile.")->group(function () {
    Route::get("/index", "ProfileController@index")->name("index");
    Route::post("/Update", "ProfileController@Update")->name("update");
    Route::post("/ChanagePassword", "ProfileController@ChanagePassword")->name("ChanagePassword");
});


Route::prefix("Notification")->name("Notification.")->group(function () {

    Route::get("/SendForCustom", "NotificationsController@ShowSendForCustom")->name("SendForCustom");
    Route::get("/SendForAll", "NotificationsController@ShowSendForAll")->name("SendForAll");
    Route::post("/send", "NotificationsController@send")->name("send");

});

Route::prefix("adminRole")->name("adminRole.")->group(function () {
    Route::get("/index", "ManagerAdminController@index")->name("index");
    Route::get("/create", "ManagerAdminController@create")->name("create");
    Route::get("/show/{id}", "ManagerAdminController@show")->name("show");
    Route::post("/store", "ManagerAdminController@store")->name("store");
    Route::get("/edit/{id}", "ManagerAdminController@edit")->name("edit");
    Route::post("/update", "ManagerAdminController@update")->name("update");
    Route::get("/destroy/{id}", "ManagerAdminController@destroy")->name("destroy");
});

Route::prefix("specialization")->name("specialization.")->group(function (){
    Route::get("/index", [SpecializationController::class,'index'])->name("index");
    Route::get("/create", [SpecializationController::class,'create'])->name("create");
    Route::post("/store", [SpecializationController::class,'store'])->name("store");
    Route::get("/edit/{id}", [SpecializationController::class,'edit'])->name("edit");
    Route::post("/update/{id}", [SpecializationController::class,'update'])->name("update");
    Route::delete("/destroy", [SpecializationController::class,'destroy'])->name("destroy");
    Route::get("/cancel", [SpecializationController::class,'cancel'])->name("cancel");
});

Route::prefix("doctor")->name("doctor.")->group(function (){
    Route::get("/index", [DoctorController::class,'index'])->name("index");
    Route::get("/create", [DoctorController::class,'create'])->name("create");
    Route::post("/store", [DoctorController::class,'store'])->name("store");
    Route::get("/edit/{id}", [DoctorController::class,'edit'])->name("edit");
    Route::post("/update/{id}", [DoctorController::class,'update'])->name("update");
    Route::delete("/destroy", [DoctorController::class,'destroy'])->name("destroy");
    Route::get("/cancel", [DoctorController::class,'cancel'])->name("cancel");
});

Route::prefix("notification-reminder")->name("notification-reminder.")->group(function (){
    Route::get("/index", [BookingNotificationReminderController::class,'index'])->name("index");
    Route::get("/create", [BookingNotificationReminderController::class,'create'])->name("create");
    Route::post("/store", [BookingNotificationReminderController::class,'store'])->name("store");
    Route::get("/edit/{id}", [BookingNotificationReminderController::class,'edit'])->name("edit");
    Route::post("/update/{id}", [BookingNotificationReminderController::class,'update'])->name("update");
    Route::delete("/destroy", [BookingNotificationReminderController::class,'destroy'])->name("destroy");
    Route::get("/cancel", [BookingNotificationReminderController::class,'cancel'])->name("cancel");
});

Route::prefix("category-branch")->name("category_branch.")->group(function (){
    Route::get("/","CategoryBranchController@index")->name("index");
    Route::get("/create", "CategoryBranchController@create")->name("create");
    Route::post("/", "CategoryBranchController@store")->name("store");
    Route::get("/{id}/edit", "CategoryBranchController@edit")->name("edit");
    Route::put("/{id}", "CategoryBranchController@update")->name("update");
    Route::delete("/{id}", "CategoryBranchController@destroy")->name("destroy");
});

Route::prefix("branches")->name("branches.")->group(function (){
    Route::get("/","BranchController@index")->name("index");
    Route::get("/create", "BranchController@create")->name("create");
    Route::post("/", "BranchController@store")->name("store");
    Route::get("/{id}/edit", "BranchController@edit")->name("edit");
    Route::put("/{id}", "BranchController@update")->name("update");
    Route::delete("/{id}", "BranchController@destroy")->name("destroy");
});

Route::prefix("categories")->name("categories.")->group(function (){
    Route::get("/","CategoryController@index")->name("index");
    Route::get("/create", "CategoryController@create")->name("create");
    Route::post("/", "CategoryController@store")->name("store");
    Route::get("/{id}/edit", "CategoryController@edit")->name("edit");
    Route::put("/{id}", "CategoryController@update")->name("update");
    Route::delete("/{id}", "CategoryController@destroy")->name("destroy");
});

Route::prefix("items")->name("items.")->group(function (){
    Route::get("/","ItemController@index")->name("index");
    Route::get("/create", "ItemController@create")->name("create");
    Route::post("/", "ItemController@store")->name("store");
    Route::get("/{id}/edit", "ItemController@edit")->name("edit");
    Route::put("/{id}", "ItemController@update")->name("update");
    Route::delete("/{id}", "ItemController@destroy")->name("destroy");

    Route::prefix("/{item_id}/add-ons")->name("add_ons.")->group(function (){
        Route::get("/","AddOnController@index")->name("index");
        Route::get("/create", "AddOnController@create")->name("create");
        Route::post("/", "AddOnController@store")->name("store");
        Route::get("/{id}/edit", "AddOnController@edit")->name("edit");
        Route::put("/{id}", "AddOnController@update")->name("update");
        Route::delete("/{id}", "AddOnController@destroy")->name("destroy");
    });
});

Route::prefix("brands")->name("brands.")->group(function (){
    Route::get("/","BrandController@index")->name("index");
    Route::get("/create", "BrandController@create")->name("create");
    Route::post("/", "BrandController@store")->name("store");
    Route::get("/{id}/edit", "BrandController@edit")->name("edit");
    Route::put("/{id}", "BrandController@update")->name("update");
    Route::delete("/{id}", "BrandController@destroy")->name("destroy");
});

Route::get("orders","OrderController@index")->name("orders.index");
Route::get("CashierOrders","OrderController@posOrders")->name("orders.posOrders");
Route::get("orders/{id}/details","OrderController@details")->name("orders.details");
Route::post("orders/export/excel","OrderController@exportExcelFile")->name("orders.export.excel");
Route::resource("slider_market", "SliderController");



Route::resource("WelcomeMessage", "WelcomeMessageController");



