@extends("layouts.admin.app")
@section("page-title")
    {{__("Dashboard")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1> {{__("Social media")}}</h1>
            <p>{{__("Edit Social media")}}</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="{{route("admin.social-media.index")}}">{{__("Social media")}}</a></li>
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
                <h3 class="tile-title">{{__("Edit New Social media")}}</h3>
                <div class="tile-body">
                    <form method="post" action="{{route("admin.social-media.update", $socialMedia->id)}}" enctype="multipart/form-data">

                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Type")}}</label>
                                    <input class="form-control @if($errors->has('type')) is-invalid @endif" type="text" name="type" placeholder="{{__("Enter Type")}}" value="{{$socialMedia->type}}">
                                </div>
                                @error("type")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Url")}}</label>
                                    <input class="form-control @if($errors->has('url')) is-invalid @endif" type="text" name="url" placeholder="{{__("Enter Url")}}" value="{{$socialMedia->url}}">
                                </div>
                                @error("url")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Social Media Photo")}}</label>
                                    <div>
                                        <button class="btn btn-primary form-control button-upload-file" >
                                            <input class="input-file show-uploaded" data-upload-type="single" data-imgs-container-class="uploaded-images" type="file" name="social_media_image">
                                            <span class="upload-file-content">
                                                <i class="fas fa-upload fa-lg upload-file-content-icon left"></i>
                                                <span class="upload-file-content-text">{{__("Upload Photo")}}</span>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                                @error("social_media_image")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-5 col-sm-6">
                            <div class="uploaded-images"></div>
                        </div>
                        <div class="tile-footer">
                            <button  type="submit" class="btn btn-primary">
                                <i class="fa fa-fw fa-lg fa-check-circle"></i> {{__("Create")}}
                            </button>
                            <button class="btn btn-primary" type="submit" form="cancelForm" ><i class="fa fa-fw  fa-window-close"></i> {{__("cancel")}}</button>
                        </div>
                    </form>
                    <form action="{{route("admin.social-media.cancel")}}" method="get" id="cancelForm" class="mt-5">
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section("scripts")





@endsection
