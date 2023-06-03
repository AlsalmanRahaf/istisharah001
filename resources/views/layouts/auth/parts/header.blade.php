<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Main CSS-->
{{--    <link rel="stylesheet" type="text/css" href="{{asset('css/app.css')}}">--}}
<!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset("assets/css/main.css")}}">

    <link rel="stylesheet" type="text/css" href="{{asset("assets/css/master.css")}}">

    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/lib/all.min.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/' . app()->getLocale() . '/custom.css')}}">


    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Login - Vali Admin</title>
</head>
