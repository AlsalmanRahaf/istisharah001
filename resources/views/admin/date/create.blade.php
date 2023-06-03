@extends("layouts.admin.app")
@section("page-title")
    {{__('Create Admin')}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>{{__('Create Appointment')}}</h1>
            <p>{{__('Create Appointment')}}</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__('Appointment')}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__('Create Appointment')}}</a></li>
        </ul>
    </div>
@endsection
@section("content")
    <div class="row">
        <div class="col-lg-10 col-sm-12 m-auto">
            <div class="tile">
                <h3 class="tile-title">{{__('Create Appointment')}}</h3>
                <div class="tile-body d-flex flex-lg-row flex-sm-column flex-md-column justify-content-lg-around justify-content-sm-centers m-5">
                    <livewire:create-exist-user-appointment />
                    <livewire:create-new-user-appointment />
                </div>
            </div>
        </div>
    </div>
@endsection
@section("scripts")
    <script type="text/javascript">
            function Buttontoggle()
        {
            var t = document.getElementById("myButton");
            if(t.value=="Create Appointment For New User"){
                $("#data").css("display","block");
                $("#userData").css("display","none");
                $("#myButtonUser").css("display","block");

            }
        }
        function UserButtontoggle(){
            var t = document.getElementById("myButtonUser");
            if(t.value=="Show User Data"){
                $("#userData").css("display","block");
                $("#myButton").css("display","block");
                $("#data").css("display","none");
                $("#data").css("display","none");
            }
        }
    </script>
@endsection

