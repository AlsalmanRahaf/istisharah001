@extends("layouts.admin.app")
@section("page-title")
    {{__("Edit advertisement")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>{{__("advertisement")}}</h1>
            <p>{{__("Edit  advertisement")}}</p>
        </div>

        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="{{route("admin.Ads.index")}}">{{__("All advertisement")}}</a></li>
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
                <h3 class="tile-title">{{__("Edit advertisement")}}</h3>
                <div class="tile-body">
                    <form method="post" action="{{route("admin.Ads.update",$ads->id)}}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')


                        <div class="row">
                            @switch($ads->type)
                                @case(1)
                                <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="control-label">{{__("Add Text")}}</label>
                                    <div>
                                        <textarea cols="2" rows="4" name="Text"  class="form-control" >{{$ads->Ads_text->Data}}</textarea>
                                    </div>
                                </div>
                                </div>
                                @break;

                                @case(2)
                                <div class="col-lg-4 ">
                                    <div class="form-group">
                                        <img src="{{$ads->getFirstMediaFile('Ads')[0]->url}}" width="100px" height="100px" />
                                        <div>
                                            <label class="control-label d-block">{{__("upload")}}</label>
                                            <button class="btn btn-primary form-control button-upload-file mt-2" >
                                                <input class="input-file show-uploaded" data-upload-type="multi" data-imgs-container-class="uploaded-images" multiple  type="file" name="Ads_photo[]" accept="image/png, image/gif, image/jpeg">
                                                <span class="upload-file-content">
                                                <i class="fas fa-upload fa-lg upload-file-content-icon left"></i>
                                                <span class="upload-file-content-text">{{__("Upload Photo")}}</span>
                                            </span>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-5 col-sm-6">
                                        <div  class="uploaded-images"></div>
                                    </div>

                                    @error("ads_file")
                                    <div class="input-error text-danger" style="max-height: 100px;max-width: 100px">{{$message}}</div>
                                    @enderror
                                    <div class="col-sm-2">
                                    </div>
                                </div>
                                @break;
                                @case(3)

                                <div class="row ads-video " >
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <video width="120px" height="120px" controls>
                                                <source src="{{$ads->getFirstMediaFile('Ads')[0]->url}}" >
                                            </video>
                                            <label class="control-label">{{__("upload video")}}</label>
                                            <div>
                                                <button class="btn btn-primary form-control button-upload-file" >
                                                    <input class="input-file show-uploaded" data-upload-type="video" data-container-class-video="uploaded-video"  type="file"  name="Ads_video" accept="video/mp4,video/x-m4v,video/*">
                                                    <span class="upload-file-content">
                                                <i class="fas fa-upload fa-lg upload-file-content-icon left"></i>
                                                <span class="upload-file-content-text">{{__("Upload video")}}</span>
                                            </span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                        <div class="uploaded-video"></div>
                                </div>
                                @break;
                                @case(4)

                                <div class="row ads-mp3 " >
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <audio controls>
                                                <source src="{{$ads->getFirstMediaFile('Ads')[0]->url}}" type="audio/mpeg">
                                                Your browser does not support the audio element.
                                            </audio>
                                            <label class="control-label">{{__("upload audio")}}</label>
                                            <div>
                                                <button class="btn btn-primary form-control button-upload-file" >
                                                    <input class="input-file show-uploaded" data-upload-type="mp3" data-container-class-audio="uploaded-mp3"  type="file" name="Ads_mp3" accept="audio/mp3,audio/*;capture=microphone">
                                                    <span class="upload-file-content">
                                                <i class="fas fa-upload fa-lg upload-file-content-icon left"></i>
                                                <span class="upload-file-content-text">{{__("Upload Audio")}}</span>
                                            </span>
                                                </button>
                                            </div>
                                        </div>
                                    </div><br>
                                    <div class="uploaded-mp3 d-block">
                                        <div class=""></div>
                                    </div>
                                </div>

                                @break;
                            @endswitch
                                <div class="col-lg-4  ">
                                <div class="form-group">
                                    <img src="{{$ads->getFirstMediaFile('Logo')->url}}" width="100px" height="100px" />
                                    <div>
                                        <label class="control-label d-block">{{__("upload Logo")}}</label>
                                        <button class="btn btn-primary form-control button-upload-file mt-2" >
                                            <input class="input-file show-uploaded" data-upload-type="single" data-imgs-container-class="uploaded-images-logo" multiple type="file" name="logo" accept="image/png, image/gif, image/jpeg">
                                            <span class="upload-file-content">
                                                <i class="fas fa-upload fa-lg upload-file-content-icon left"></i>
                                                <span class="upload-file-content-text">{{__("Upload logo")}}</span>
                                            </span>
                                        </button>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-5 col-sm-6 ">
                                    <div  class="uploaded-images-logo"></div>
                                </div>

                                @error("logo")
                                <div class="input-error text-danger" style="max-height: 100px;max-width: 100px">{{$message}}</div>
                                @enderror
                                <div class="col-sm-2">
                                </div>
                            </div>

                            <div class="col-lg-3 ">
                                <label class="control-label">{{__("status")}}</label>
                                <div class="toggle-flip">
                                    <label>
                                        <input type="checkbox" name="status" {{ checked("status", 1, $ads) }}><span class="flip-indecator" data-toggle-on="{{__("ON")}}" data-toggle-off="{{__("OFF")}}"></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="tile-footer">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> {{__("Update")}}</button>
{{--                            <button class="btn btn-primary" type="submit" form="cancelForm" ><i class="fa fa-fw  fa-window-close"></i> {{__("cancel")}}</button>--}}
                        </div>
                    </form>

{{--                    <form action="{{route("admin.sliders.cancel")}}" method="get" id="cancelForm" class="mt-5">--}}
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection

@section("scripts")


@endsection
