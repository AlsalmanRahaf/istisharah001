<?php
namespace App\Providers\RoutesProvider;

use App\Providers\RoutesProvider\Providers\IRoutesProvider;

class RouteMapping
{
    public static function map(IRoutesProvider $route){
        $route->mapping();
    }
}
