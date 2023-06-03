@extends('layouts.auth.app')

@section("css-links")
    <link rel="stylesheet" href="{{asset("assets/css/" . app()->getLocale() . "/pages/user_login.css")}}">
@endsection

@section('content')
    @if(count($errors) > 0 )
    <div class="login-errors">
        <ul >
            @foreach($errors->all() as $key => $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="login-box verfication-phone">
        <div class="login-form">
            @csrf
            <h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i>SIGN IN</h3>
            <div class="form-group">
                <label class="control-label">PHONE NUMBER</label>
                <input id="number" class="form-control" type="text" placeholder="0791234567" name="phone_number" value="795011901" autofocus>
            </div>
            <div id="recaptcha-container" style="display: none"></div><br>
            <div class="form-group btn-container">
                <button id="Send" class="btn btn-primary btn-block"><i class="fa fa-sign-in fa-lg fa-fw"></i>SIGN IN</button>
            </div>
        </div>
        <div class="verify-code-form" >
            @csrf
            <h3 class="login-head"><i class="fa fa-lg fa-fw fa-lock"></i>Verfication Code</h3>
            <div class="resend-box">
                <p>Don't receive the code ?</p>
                <button class="btn btn-primary btn-block" id="ResendCode" data-text-after-finish = "Resend Code" disabled><span class="timer">60</span> S</button>
                <button class="btn btn-primary btn-block" id="ChangeNumber">Change Phone Number</button>
            </div>
            <div class="form-group">
                <label class="control-label">Code</label>
                <input id="verificationCode" class="form-control"
                       type="text" placeholder="123456.."
                       name="email" value="654321">
            </div>
            <div class="form-group btn-container">
                <button class="btn btn-primary btn-block" id="Verify" data-url-call = "{{route("user.auth.login")}}">NEXT <i class="fa fa-caret-right fa-lg fa-fw" style="font-size: 18px"></i></button>
            </div>

        </div>


    </div>
@endsection

@section("scripts")
    <script type="module" src="{{asset("assets/js/modules/packages_init/firebase.js")}}"></script>
    <script src="https://www.gstatic.com/firebasejs/6.0.2/firebase.js"></script>

    <script type="text/javascript">
       //$('.login-box').toggleClass('flipped');
        // Login Page Flipbox control
        $('.login-content [data-toggle="flip"]').click(function() {
            $('.login-box').toggleClass('flipped');
            return false;
        });

    </script>
    <script type="module" src="{{asset("assets/js/pages/user_login.js")}}"></script>


@endsection

