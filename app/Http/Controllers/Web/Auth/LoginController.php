<?php

namespace App\Http\Controllers\Web\Auth;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\WebAuthentication;
use Firebase\Auth\Token\Exception\InvalidToken;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Kreait\Laravel\Firebase\Facades\Firebase;
use function Symfony\Component\String\b;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */


    use  WebAuthentication, AuthenticatesUsers {
        WebAuthentication::guard insteadof AuthenticatesUsers;
        WebAuthentication::logout insteadof AuthenticatesUsers;
        WebAuthentication::attemptLogin insteadof AuthenticatesUsers;
        WebAuthentication::username insteadof  AuthenticatesUsers;
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
        $this->initizationLoginField();
    }


    public function showLoginForm()
    {
        switch (true){
            case request()->is(App::getLocale() . "/admin*"):
                return $this->showAdminLoginForm();
            break;
            default:
                abort(404);
            break;
        }
    }

    public function showAdminLoginForm()
    {
        return view('admin.auth.login');
    }

//    public function showUserLoginForm()
//    {
//        return view('user.auth.login');
//    }




    public function userLogin(Request $request){
        // Launch Firebase Auth
        $auth = Firebase::auth();
        $firebaseToken = $request->firebase_token;

        try { // Try to verify the Firebase credential token with Google

            $verifiedIdToken = $auth->verifyIdToken($firebaseToken);
            $uid = $verifiedIdToken->claims()->get('sub');
            $user = User::where('firebase_uid',$uid)->where(function ($q){
                $q->whereIn("type", ["SP","CC"]);
            })->first();
            if($user){
                 $guard = $user->type == "SP" ? "supplier" : "contracters_company";
                $redirect = $this->redirectUserAfterLogin($guard);
                Auth::guard($guard)->login($user);
                $data["redirectUrl"] = env("APP_URL") . $redirect;
                return JsonResponse::data($data)->message("login success")->send();
            }else{
                return JsonResponse::error()->message("user not found")->changeCode(401)->changeStatusNumber("S401")->send();
            }

        } catch (InvalidToken $e) { // If the token is invalid (expired ...)
            return JsonResponse::error()->message('token not valid')->changeCode(401)->changeStatusNumber('S401')->send();
        } catch (\InvalidArgumentException $e) {
            return JsonResponse::error()->message($e->getMessage())->changeCode(401)->changeStatusNumber('S401')->send();
        }
    }






}
