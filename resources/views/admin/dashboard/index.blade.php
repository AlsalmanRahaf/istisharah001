@extends("layouts.admin.app")
@section("page-title")
    Dashboard
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> Dashboard</h1>
            <p>Istishara Dashboard</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        </ul>
    </div>
@endsection

@section("content")
    @if(hasPermissions("view-dashboard"))
    <div class="row">
        <div class="col-md-6 col-lg-3 pointer"   onclick="window.location.href='{{ route("admin.users.index",["type"=>"u"])}}'">
            <div class="widget-small primary coloured-icon"><i class="icon fa fa-user fa-3x"></i>
                <div class="info">
                    <h4>Users</h4>
                    <p><b>{{$counter->users ?? 0}}</b></p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="widget-small primary coloured-icon pointer" onclick="window.location.href='{{ route("admin.admins.index") }}'"><i class="icon fas fa-user-shield fa-3x" style="background: #1e7e34"></i>
                <div class="info">
                    <h4>Admins</h4>
                    <p><b>{{$counter->admins ?? 0}}</b></p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="widget-small warning coloured-icon pointer" onclick="window.location.href='{{ route("admin.users.index",["type"=>"c"])}}'"><i class="icon fa fa-user-tie fa-3x"></i>
                <div class="info">
                    <h4>Consultants</h4>
                    <p><b>{{$counter->consultants ?? 0}}</b></p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="widget-small danger coloured-icon pointer" onclick="window.location.href='{{ route("admin.users.index",["type"=>"cph"]) }}'"><i class="icon fas fa-users fa-3x"></i>
                <div class="info">
                    <h4>Consultants Pharmacist</h4>
                    <p><b>{{$counter->consultantsPharmacist ?? 0}}</b></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-lg-3">
            <div class="widget-small primary coloured-icon pointer" onclick="window.location.href='{{ route("admin.users.index",["type"=>"cn"])}}'"><i class="icon fas fa-users fa-3x"></i>
                <div class="info">
                    <h4>Consultants Nutrition</h4>
                    <p><b>{{$counter->consultantsNutrition ?? 0}}</b></p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="widget-small primary coloured-icon pointer" onclick="window.location.href='{{ route("admin.users.index",["type"=>"cd"])}}'"><i class="icon fas fa-users fa-3x" style="background: #1e7e34"></i>
                <div class="info">
                    <h4>Consultant Diabetes</h4>
                    <p><b>{{$counter->consultantDiabetes ?? 0}}</b></p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="widget-small warning coloured-icon pointer" onclick="window.location.href='{{ route("admin.Ads.index")}}'"><i class="icon fas fas fa-ad fa-3x"></i>
                <div class="info">
                    <h4>Advertisements</h4>
                    <p><b>{{$counter->advertisements ?? 0}}</b></p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="widget-small primary coloured-icon pointer" onclick="window.location.href='{{ route("admin.social-media.index")}}'"><i class="icon fas fa-mobile-alt fa-3x"></i>
                <div class="info">
                    <h4>Social Media</h4>
                    <p><b>{{$counter->social_media ?? 0}}</b></p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="widget-small primary coloured-icon pointer" onclick="window.location.href='{{ route("admin.sliders.index")}}'"><i class="icon fas fa-sliders-h fa-3x"></i>
                <div class="info">
                    <h4>Sliders</h4>
                    <p><b>{{$counter->sliders ?? 0}}</b></p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="widget-small primary coloured-icon pointer" onclick="window.location.href='{{ route("admin.users.index",["type"=>"a"]) }}'"><i class="icon fas fa-user-shield fa-3x" style="background: #1e7e34"></i>
                <div class="info">
                    <h4>App admins</h4>
                    <p><b>{{$counter->Appadmins ?? 0}}</b></p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="widget-small warning coloured-icon pointer" onclick="window.location.href='{{ route("admin.consultations.Custom")}}'"><i class="icon  fas fa-users fa-3x"></i>
                <div class="info">
                    <h4>Other consultations</h4>
                    <p><b>{{$counter->Other_consultations ?? 0}}</b></p>
                </div>
            </div>
        </div>
    </div>





    @endif
    @endsection

@section("scripts")
   <script>
       // let socket = io("http://soket.dashboard.isteshara.digisolapps.com:8101/userChat/userSocket");
       // console.log(socket.connected)
       // socket.on('error', err => {
       //     // An error occurred.
       //     console.log(err);
       // })
       // socket.on("test", () => {
       //  console.log("hhh");
       // });
   </script>

@endsection
