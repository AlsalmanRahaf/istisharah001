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
                                <select id="select-type-ads" required class="form-control" name="type">
                                    <option value=" ">Select one</option>
                                    <option value=1>send for all users</option>
                                    <option value=1>send for custom user</option>
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



                <div class="row dis-none ads-photo " >
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="control-label">{{__("upload photo")}}</label>
                            <div>
                                <button class="btn btn-primary form-control button-upload-file" >
                                    <input class="input-file show-uploaded" data-upload-type="multi" data-imgs-container-class="uploaded-images" multiple type="file" name="Ads_photo[]" accept="image/png, image/gif, image/jpeg">
                                    <span class="upload-file-content">
                                                <i class="fas fa-upload fa-lg upload-file-content-icon left"></i>
                                                <span class="upload-file-content-text">{{__("Upload Photo")}}</span>
                                            </span>
                                </button>
                            </div>
                        </div>

                        @error("Ads_photo")
                        <div class="input-error text-danger">{{$message}}</div>
                        @enderror
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
                                    <input class="input-file show-uploaded" data-upload-type="multi" data-imgs-container-class="uploaded-images" multiple type="file"  name="Ads_video[]" accept="video/mp4,video/x-m4v,video/*">
                                    <span class="upload-file-content">
                                                <i class="fas fa-upload fa-lg upload-file-content-icon left"></i>
                                                <span class="upload-file-content-text">{{__("Upload video")}}</span>
                                            </span>
                                </button>
                            </div>
                        </div>

                        @error("Ads_video")
                        <div class="input-error text-danger">{{$message}}</div>
                        @enderror
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


