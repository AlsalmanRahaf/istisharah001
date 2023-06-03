<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\App;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */


    public function handle(Request $request, Closure $next)
    {
        $lang = "en";
        if(isset($request->lang) && !empty($request->lang) &&
            array_key_exists($request->lang, config("app.languages"))){
            $lang = $request->lang;
        }
       // var_dump($lang);die;
        App::setLocale($lang);

        unset($lang);
        return $next($request);
    }
}
