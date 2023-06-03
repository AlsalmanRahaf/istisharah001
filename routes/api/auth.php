<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;

Route::post("login", [LoginController::class, "login"]);
Route::post("login-test", [LoginController::class, "testLogin"]);
Route::post("register", "RegisterController@register");
Route::post("logout", [LoginController::class, "logout"]);
