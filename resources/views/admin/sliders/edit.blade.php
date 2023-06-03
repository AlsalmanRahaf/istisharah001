@extends("layouts.admin.app")
@section("page-title")
    {{__("Edit Slider")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>{{__("Slider")}}</h1>
            <p>{{__("Edit  slider")}}</p>
        </div>

        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="{{route("admin.sliders.index")}}">{{__("All sliders")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("Edit")}}</a></li>
        </ul>
    </div>
@endsection

@section("css-links")
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection

@section("content")

    <div class="row">
        <div class="col-lg-10 m-auto">
            <div class="tile">
                <h3 class="tile-title">{{__("Edit slider")}}</h3>
                <div class="tile-body">
                    <form method="post" action="{{route("admin.sliders.update",[$Slider->id])}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("slider Photo")}}</label>
                                    <div>
                                        <button class="btn btn-primary form-control button-upload-file" >
                                            <input class="input-file show-uploaded" data-upload-type="single" data-imgs-container-class="uploaded-images"  type="file" name="slider_photo" accept="image/png, image/gif, image/jpeg">
                                            <span class="upload-file-content">
                                                <i class="fas fa-upload fa-lg upload-file-content-icon left"></i>
                                                <span class="upload-file-content-text">{{__("Upload Photo")}}</span>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                                @error("slider_photo")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-3 col-md-5 col-sm-6">
                                <div class="uploaded-images"></div>
                            </div>
                        </div>

                        <div class="row">
                           <div class="form-group">
                                <div class="col-lg-6">
                                <label class="control-label">{{__("status")}}</label>
                                <div class="toggle-flip">
                                    <label>
                                        <input type="checkbox" name="status" {{ checked("status", 1, $Slider) }}><span class="flip-indecator" data-toggle-on="{{__("ON")}}" data-toggle-off="{{__("OFF")}}"></span>
                                    </label>
                                </div>
                            </div>
                           </div>
                        </div>

                        <div class="tile-footer">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> {{__("Update")}}</button>
                            <button class="btn btn-primary" type="submit" form="cancelForm" ><i class="fa fa-fw  fa-window-close"></i> {{__("cancel")}}</button>
                        </div>
                    </form>

                    <form action="{{route("admin.sliders.cancel")}}" method="get" id="cancelForm" class="mt-5">
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection

@section("scripts")


@endsection
