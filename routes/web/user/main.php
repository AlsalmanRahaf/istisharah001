<?php

use Illuminate\Support\Facades\Auth;

Auth::get("/",function (){
   return view("user.index");
});
