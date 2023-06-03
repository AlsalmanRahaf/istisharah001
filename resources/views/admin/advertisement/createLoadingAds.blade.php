@extends("layouts.admin.app")
@section("page-title")
    {{__("create advertisement")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>{{__("create loading advertisement")}}</h1>
        </div>

        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="{{route("admin.Ads.loadingAds")}}">{{__("loading  advertisement")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("Create loading advertisement")}}</a></li>
        </ul>
    </div>
@endsection


@section("content")

    <div class="row">
        <div class="col-lg-10 m-auto">
            <div class="tile">
                <h3 class="tile-title">{{__("Create advertisement")}}</h3>
                <div class="tile-body">
                    <form method="post" action="{{route("admin.Ads.storeLoadingAds")}}" enctype="multipart/form-data">
                        @csrf


                        <div class="row" >
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("upload photo")}}</label>
                                    <div>
                                        <button class="btn btn-primary form-control button-upload-file" >
                                            <input class="input-file show-uploaded" data-upload-type="single" data-imgs-container-class="uploaded-images"  type="file" name="Ads_photo" accept="image/png, image/gif, image/jpeg">
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
@endsection


