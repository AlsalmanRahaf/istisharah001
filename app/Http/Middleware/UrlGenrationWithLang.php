<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

class UrlGenrationWithLang
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    protected $url;
    public function __construct(UrlGenerator $url)
    {
        $this->url = $url;
    }

    public function handle(Request $request, Closure $next)
    {
        $this->url->defaults([
            'lang' => App::getLocale(),
        ]);
        return $next($request);
    }
}
