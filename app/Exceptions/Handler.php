<?php

namespace App\Exceptions;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Helpers\ApiResponse\Json\Senders\SendError;
use App\Helpers\ApiResponse\Json\Senders\SendSuccess;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\App;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {

        });
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        $lang = App::getLocale();
        return $request->expectsJson() || $request->is("api/*")
            ? JsonResponse::error()->message("unauthenticated")->changeCode(401)->changeStatusNumber("S401")->send()
            : redirect()->guest($request->is($lang . "/admin*") ?  route('login') : route("user.auth.show-login"));
    }
}
