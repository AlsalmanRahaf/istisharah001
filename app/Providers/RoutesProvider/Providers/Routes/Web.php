<?php


namespace App\Providers\RoutesProvider\Providers\Routes;


use App\Providers\RoutesProvider\Providers\IRoutesProvider;
use Illuminate\Support\Facades\Route;

class Web implements IRoutesProvider
{

    public function mapping($namespace = "App\Http\Controllers\Web")
    {

        Route::group(["middleware" => ['web', 'lang', 'url.lang.default','permissions'], "namespace" => $namespace, "prefix" => "/{lang}/"],function ($lang = "en"){
            Route::group([],base_path('routes/web/main.php'));
            Route::group([],base_path('routes/web/auth.php'));

            // Admin Group
            Route::middleware("auth:admin")->name("admin.")->prefix("admin")
                ->namespace("Admin")->group(function (){
                    Route::group([],base_path('routes/web/admin/main.php'));
                });


            Route::prefix("ajax")->namespace("Ajax")->name("ajax.")->group(function (){
                Route::group([],base_path('routes/web/ajax/admin.php'));
        });
        });


    }
}
