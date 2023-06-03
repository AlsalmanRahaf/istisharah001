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

<div class="row">
    <div class="col-lg-10 m-auto">
        <div class="tile">
            <h3 class="tile-title">{{__("Create notification")}}</h3>
            <div class="tile-body">
                <form method="post" action="{{route("admin.Ads.store")}}" enctype="multipart/form-data">
                @csrf
                <div class="row ">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="control-label">{{__("select type")}}</label>
                            <div>
                                <select id="select-type-ads" required class="form-control" name="type" onchange="alert(this.value)">
                                    <option>Select one</option>
                                    <option value=1>send for all users</option>
                                    <option value=2>send for custom user</option>
                                </select>
                            </div>
                        </div>

                        @error("type")
                        <div class="input-error text-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="col-lg-3 col-md-5 col-sm-6">
                        <div class="uploaded-images"></div>
                    </div>
                </div>

                <div class="row dis-none ads-text"  >
                    <div class="col-lg-6">
                        <div class="form-group">

                            <div>
                                <label class="control-label">{{__("Title")}}</label>
                                <input type="" name="Title"  class="form-control" ></textarea>
                            </div>

                            <div>
                                <label class="control-label">{{__("Description")}}</label>
                                <textarea cols="2" rows="4" name="Description"  class="form-control" ></textarea>
                            </div>
                        </div>
                        @error("Text")
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


