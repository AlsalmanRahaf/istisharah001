 <meta name="description" content="Bunyan">

{{--    <link rel="icon" href="https://goldenmealpro.digisolapps.com/golden_meal_backend/public/assets/logo.svg">--}}
<!-- Open Graph Meta-->
<meta property="og:type" content="website">
<meta property="og:site_name" content="Golden Meal">
<meta property="og:title" content="Vali - Free Bootstrap 4 admin theme">
<meta property="og:url" content="http://pratikborsadiya.in/blog/vali-admin">
<meta property="og:description" content="Vali is a responsive and free admin theme built with Bootstrap 4, SASS and PUG.js. It's fully customizable and modular.">
<meta name="csrf-token" content="{{ csrf_token() }}">
 <link rel="icon" href="https://dashboard.isteshara.digisolapps.com/assets/logo-color.png">

 <title>
    @hasSection ('page-title')
        @yield('page-title') - {{ config("app.name") }}
    @else
        {{ config("app.name") }}
    @endif
</title>

 <meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

 <!-- Google fonts -->

 <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;700;900&display=swap" rel="stylesheet">

<!-- Main CSS-->
<link rel="stylesheet" type="text/css" href="{{asset("assets/css/main.css")}}">


<link rel="stylesheet" type="text/css" href="{{asset('assets/css/lib/all.min.css')}}">

 <link rel="stylesheet" type="text/css" href="{{asset("assets/css/master.css")}}">

<link rel="stylesheet" type="text/css" href="{{asset('assets/css/' . app()->getLocale() . '/custom.css')}}">

 <!-- Google fonts -->
 <link rel="preconnect" href="https://fonts.googleapis.com">
 <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
 <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;700;900&display=swap" rel="stylesheet" type="text/css">
 <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.3.3/css/bootstrap-colorpicker.min.css" rel="stylesheet">
 <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

 @livewireStyles
 <!-- Font-icon css-->
@hasSection("css-links")
    @yield("css-links")
@endif
