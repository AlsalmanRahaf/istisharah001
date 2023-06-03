<?php


use Illuminate\Support\Facades\Route;

//user
Route::post("/update_user_status", "UsersController@update")->name("users.update");

//ads
Route::post("/update_Ads_status", "AdsController@update")->name("Ads.update");
Route::post("/update_loadingAds_status", "AdsController@updateloadingAds")->name("Ads.updateloadingAds");


//slider

Route::prefix("option")->name("option.")->group(function (){
    Route::post("/delete", "AddOnsOptionsController@destroy")->name("delete");
});

Route::post("/update_Slider_status", "SliderController@update")->name("Slider.update");
Route::post("/update_other_consultant_status", "CustomConsultationController@update")->name("other.update");


//admin
Route::post("/update_Admin_status", "AdminController@update")->name("Admin.update");

