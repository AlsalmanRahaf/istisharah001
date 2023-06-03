<?php

namespace App\Traits;

use App\Models\Admin;
use App\Models\UsersDashboard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

trait WebAuthentication
{

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $loginField = "email";
    protected $redirectTo;

    public function username()
    {
        return $this->loginField;
    }

    public function redirectUserAfterLogin($guard){
        switch ($guard){
            case "admin":
                return "/" . App::getLocale() . "/admin";
            break;
        }
        return "/";
    }

    public function redirectAfterLogout($guard): string
    {
        $route = "/" . App::getLocale();
        switch ($guard){
            case "admin":
                $route .=  "/admin/login";
                break;
        }
        return $route;
    }

    public function logout(Request $request)
    {
        $guard = $this->getCurrentGuard($request);
        $this->guard($guard)->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        if ($response = $this->loggedOut($request)) {
            return $response;
        }
        return $request->wantsJson()
            ? new \Illuminate\Http\JsonResponse([], 204)
            : redirect($this->redirectAfterLogout($guard));
    }

    protected function attemptLogin(Request $request)
    {
        if($this->isActiveAdmin($request)){
            $guard = $this->getCurrentGuard($request);
            $this->redirectTo = $this->redirectUserAfterLogin($guard);
            return $this->guard($guard)->attempt(
                $this->credentials($request), $request->filled('remember')
            );
        }else{
            return false;
        }
    }

    protected function initizationLoginField(){
//        dd(request()->input("login"));
        $loginFieldValue = request()->input("login");
//        $loginFieldValue = request()->input("login");
        if($loginFieldValue){
            $this->loginField = filter_var($loginFieldValue, FILTER_VALIDATE_EMAIL) ? "email" : "username";
            request()->merge([$this->loginField => $loginFieldValue]);
        }
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard($name = null)
    {
        return Auth::guard($name);
    }

    public function getCurrentGuard(Request $request): string
    {
        switch (true){
            case Auth::guard("admin")->check() || $request->is(App::getLocale() ."/admin*") :
                return "admin";
                break;
        }
        return 'user';
    }

    public function isActiveAdmin($request){
        $loginFild=$this->username();
        return  Admin::where([["$loginFild",$request->$loginFild],["status",1]])->exists();
    }

}
