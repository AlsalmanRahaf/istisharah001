<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        URL::defaults(['lang' => 'en']);
        defined("DS") ? null : define("DS", DIRECTORY_SEPARATOR);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    //    JsonResource::withoutWrapping();
    }
}
