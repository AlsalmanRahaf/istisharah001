
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
    <div class="verfiy-box">
        @if (session('resent'))
            <div class="alert alert-success" role="alert">
                {{ __('A fresh verification link has been sent to your email address.') }}
            </div>
        @endif
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2>Verfiy You Email</h2>
                <p>The Url verification Has Been Send Success To Your MailBox , check it please</p>
            </div>
            <div class="col-lg-12 text-center">
                <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">{{ __('Resend the To Email') }}</button>.
                </form>
            </div>
        </div>

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