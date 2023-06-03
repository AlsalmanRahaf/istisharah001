@extends("layouts.admin.app")
@section("page-title")
{{__("send notification")}}
@endSection
@section("page-nav-title")
<div class="app-title">
    <div>
        <h1><i class="fa fa-dashboard"></i>{{__("Notification")}}</h1>
        <p>{{__("Create new notification")}}</p>
    </div>

    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
        <li class="breadcrumb-item"><a href="#">{{__("Create notification")}}</a></li>
    </ul>
</div>
@endsection
@section("content")
@include("includes.dialog")

<div class="row">
    <div class="col-lg-10 m-auto">
        <div class="tile">
            <h3 class="tile-title">{{__("Create notification")}}</h3>
            <div class="tile-body">
                <form method="post" action="{{route("admin.Notification.send")}}" enctype="multipart/form-data">
                @csrf
                    <input type="hidden" class="form-control"  value=0 name="type"  class="form-control" >
                <div class="row  ads-text"  >
                    <div class="col-lg-6">
                        <select id="select-Topic" required class="form-control" name="topic">
                            <option value="users">Users</option>
                            <option value="admin">admins</option>
                            <option value="consultants">consultants</option>
                        </select>
                        <div class="form-group">

                            <div>
                                <label class="control-label">{{__("Title")}}</label>
                                <input type="" name="Title"  class="form-control" >
                            </div>
                            @error("Title")
                            <div class="mt-2 input-error text-danger">{{$message}}</div>
                            @enderror

                            <div>
                                <label class="control-label">{{__("Description")}}</label>
                                <textarea cols="2" rows="4" name="Description"  class="form-control" ></textarea>
                            </div>
                        </div>
                        @error("Description")
                        <div class="input-error text-danger">{{$message}}</div>
                        @enderror
                    </div>
                </div>

                <div class="tile-footer">
                    <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> {{__("Send")}}</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section("scripts")

@endsection


