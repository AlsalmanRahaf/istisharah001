<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::prefix("admin")->group(function(){
    Auth::routes();
});

Route::prefix("user")->middleware("guest:supplier,contracters_company,admin")->namespace("Auth")->group(function(){
    Route::get("/login", "LoginController@showLoginForm")->name("user.auth.show-login");
    Route::post("/login", "LoginController@userLogin")->name("user.auth.login");
});
Route::post("/logout", "Auth\LoginController@logout")->name("user.auth.logout");
