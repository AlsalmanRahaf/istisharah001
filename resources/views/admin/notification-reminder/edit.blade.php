@extends("layouts.admin.app")
@section("page-title")
    {{__("Dashboard")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1> {{__("notification-reminder")}}</h1>
            <p>{{__("edit-notification-reminder")}}</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="{{route("admin.notification-reminder.index")}}">{{__("notification-reminder")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("Edit")}}</a></li>
        </ul>
    </div>
@endsection

@section("css-links")
    <link rel="stylesheet" href="{{asset("assets/css/utils/week_days.css")}}">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection

@section("content")

    <div class="row">
        <div class="col-lg-10 m-auto">
            <div class="tile">
                <h3 class="tile-title">{{__("edit-notification-reminder")}}</h3>
                <div class="tile-body">
                    <form method="post" action="{{route("admin.notification-reminder.update", $duration->id)}}" enctype="multipart/form-data">

                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("duration-number")}}</label>
                                    <input class="form-control @if($errors->has('duration_number')) is-invalid @endif" type="text" name="duration_number" placeholder="{{__("enter-duration-number")}}" value="{{$duration->duration_number}}">
                                </div>
                                @error("duration_number")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("duration-type")}}</label>
                                    <select class="form-control @if($errors->has('duration_type')) is-invalid @endif" id="duration_type" name="duration_type">
                                        <option></option>
                                            <option value="Day"@if($duration->duration_type === "Day") selected @endif>{{__("day")}}</option>
                                        <option value="Hour"@if($duration->duration_type === "Hour") selected @endif>{{__("hour")}}</option>
                                        <option value="Minute"@if($duration->duration_type === "Minute") selected @endif>{{__("minute")}}</option>
                                    </select>
                                </div>
                                @error("duration_type")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-5 col-sm-6">
                            <div class="uploaded-images"></div>
                        </div>
                        <div class="tile-footer">
                            <button  type="submit" class="btn btn-primary">
                                <i class="fa fa-fw fa-lg fa-check-circle"></i> {{__("edit")}}
                            </button>
                            <button class="btn btn-primary" type="submit" form="cancelForm" ><i class="fa fa-fw  fa-window-close"></i> {{__("cancel")}}</button>
                        </div>
                    </form>
                    <form action="{{route("admin.notification-reminder.cancel")}}" method="get" id="cancelForm" class="mt-5">
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section("scripts")
    <script type="text/javascript">
        $("#duration_type").select2({
            placeholder: "{{__('select-duration-type')}}",
            width: "100%",
        });
    </script>
@endsection
