@extends("layouts.admin.app")
@section("page-title")
    {{__("create advertisement")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>{{__("advertisement")}}</h1>
            <p>{{__("Create new advertisement")}}</p>
        </div>

        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="{{route("admin.Ads.index")}}">{{__("All advertisement")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("Create")}}</a></li>
        </ul>
    </div>
@endsection


@section("content")

    <div class="row">
        <div class="col-lg-10 m-auto">
            <div class="tile">
                <h3 class="tile-title">{{__("Create advertisement")}}</h3>
                <div class="tile-body">
                    <form method="post" action="{{route("admin.Ads.store")}}" enctype="multipart/form-data">
                        @csrf

                        <div class="row" >
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="control-label">{{__("upload Logo")}}</label>
                                    <div>
                                        <button class="btn btn-primary form-control button-upload-file" >
                                            <input class="input-file show-uploaded" data-upload-type="single" data-imgs-container-class="uploaded-images-logo" multiple type="file" name="logo" accept="image/png, image/gif, image/jpeg">
                                            <span class="upload-file-content">
                                                <i class="fas fa-upload fa-lg upload-file-content-icon left"></i>
                                                <span class="upload-file-content-text">{{__("Upload logo")}}</span>
                                            </span>
                                        </button>
                                    </div>
                                </div>

                                <div  class="uploaded-images-logo"></div>

                            </div>
                        </div>




                        <div class="row ">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("select type")}}</label>
                                    <div>
                                        <select id="select-type-ads" required class="form-control" name="type">
                                            <option value=" ">Select one</option>
                                            <option value=1>Text</option>
                                            <option value=2>Photo</option>
                                            <option value=4>mp3</option>
                                            <option value=3>Video</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-5 col-sm-6">
                                <div class="uploaded-images"></div>
                            </div>
                        </div>

                        <div class="row dis-none ads-text"  >
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Add Text")}}</label>
                                    <div>
                                        <textarea cols="2" rows="4" name="Text"  class="form-control" ></textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">{{__("background Text color")}}</label>
                                    <div>
                                        <input class="form-control" readonly name="bg_color" type="text" id="color"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row dis-none ads-mp3" >
                            <div class="col-lg-6">
                                <div class="form-group">
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
                            </div>
                            <div class="uploaded-mp3">
                                <div class=""></div>
                            </div>
                        </div>

                        <div class="row dis-none ads-photo " >
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("upload photo")}}</label>
                                    <div>
                                        <button class="btn btn-primary form-control button-upload-file" >
                                            <input class="input-file show-uploaded" data-upload-type="multi" data-imgs-container-class="uploaded-images" multiple type="file" name="Ads_photo[]" accept="image/*">
                                            <span class="upload-file-content">
                                                <i class="fas fa-upload fa-lg upload-file-content-icon left"></i>
                                                <span class="upload-file-content-text">{{__("Upload Photo")}}</span>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-5 col-sm-6">
                                <div class="uploaded-images"></div>
                            </div>
                        </div>

                        <div class="row dis-none ads-video" >
                            <div class="col-lg-6">
                                <div class="form-group">
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
                            <div class="col-lg-3 col-md-5 col-sm-6">
                                <div class="uploaded-video"></div>
                            </div>
                        </div>


                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <div class="text-danger">{{$error}}</div>
                            @endforeach
                        @endif


                        <div class="tile-footer">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> {{__("Create")}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section("scripts")
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.3.3/js/bootstrap-colorpicker.min.js"></script>
    <script>
    $(function () {
    $('#color').colorpicker({
        format: 'hex'
    });
    });
    </script>

@endsection


