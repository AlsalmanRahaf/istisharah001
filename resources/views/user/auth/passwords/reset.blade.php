{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}


@extends('layouts.auth.app')

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
    <div class="login-box" style="min-height: 420px;">
        <form class="login-form" method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i>RESET PASSWORD</h3>
            <div class="form-group">
                <label class="control-label">EMAIL</label>
                <input class="form-control" type="text" placeholder="Email" name="email" autofocus value="{{ $email ?? old('email') }}">
            </div>
            <div class="form-group">
                <label class="control-label">PASSWORD</label>
                <input class="form-control" type="password" placeholder="Password" name="password">
            </div>

            <div class="form-group">
                <label class="control-label">CONFIRM PASSWORD</label>
                <input class="form-control" type="password" placeholder="Password" name="password_confirmation">
            </div>
            <div class="form-group btn-container">
                <button class="btn btn-primary btn-block"><i class="fa fa-sign-in fa-lg fa-fw"></i>Reset Password</button>
            </div>
        </form>

    </div>
@endsection

@section("scripts")
    <script type="text/javascript">
        // Login Page Flipbox control
        $('.login-content [data-toggle="flip"]').click(function() {
            $('.login-box').toggleClass('flipped');
            return false;
        });
    </script>
@endsection
