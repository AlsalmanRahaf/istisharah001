<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class Permissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::check()){
            $user = Auth::user();
            if($user->role){
                foreach ($user->role->permissions as $permission)
                    Gate::define($permission->slug,function(){
                       return true;
                    });
            }
        }
        return $next($request);
    }
}
