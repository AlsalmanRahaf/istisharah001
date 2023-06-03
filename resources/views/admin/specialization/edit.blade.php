@extends("layouts.admin.app")
@section("page-title")
    {{__("Dashboard")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1> {{__("specializations")}}</h1>
            <p>{{__("edit-specialization")}}</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="{{route("admin.specialization.index")}}">{{__("specializations")}}</a></li>
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
                <h3 class="tile-title">{{__("edit-specialization")}}</h3>
                <div class="tile-body">
                    <form method="post" action="{{route("admin.specialization.update", $specialization->id)}}" enctype="multipart/form-data">

                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("en-name")}}</label>
                                    <input class="form-control @if($errors->has('name_en')) is-invalid @endif" type="text" name="name_en" placeholder="{{__("enter-en-name")}}" value="{{$specialization->name_en}}">
                                </div>
                                @error("name_en")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("ar-name")}}</label>
                                    <input class="form-control @if($errors->has('name_ar')) is-invalid @endif" type="text" name="name_ar" placeholder="{{__("enter-ar-name")}}" value="{{$specialization->name_ar}}">
                                </div>
                                @error("name_ar")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                             </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label">{{__("en-description")}}</label>
                                        <textarea class="form-control @if($errors->has('description_en')) is-invalid @endif" name="description_en" placeholder="{{__("enter-en-description")}}" value="{{$specialization->description_en}}">{{$specialization->description_en}}</textarea>
                                    </div>
                                    @error("description_en")
                                    <div class="input-error">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label">{{__("ar-description")}}</label>
                                        <textarea class="form-control @if($errors->has('description_ar')) is-invalid @endif" type="text" name="description_ar" placeholder="{{__("enter-ar-description")}}" value="{{$specialization->description_ar}}">{{$specialization->description_ar}}</textarea>
                                    </div>
                                    @error("description_ar")
                                    <div class="input-error">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                                <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("specialization-photo")}}</label>
                                    <div>
                                        <button class="btn btn-primary form-control button-upload-file" >
                                            <input class="input-file show-uploaded" data-upload-type="single" data-imgs-container-class="uploaded-images" type="file" name="specialization_image">
                                            <span class="upload-file-content">
                                                <i class="fas fa-upload fa-lg upload-file-content-icon left"></i>
                                                <span class="upload-file-content-text">{{__("Upload Photo")}}</span>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                                @error("specialization_image")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-5 col-sm-6">
                            <div class="images">
                                <div class="uploaded-images">
                                    <img src="{{$specialization->getFirstMediaFile("Specializations")->url}}" alt="" width="90px">
                                </div>
                            </div>
                        </div>
                        <div class="tile-footer">
                            <button  type="submit" class="btn btn-primary">
                                <i class="fa fa-fw fa-lg fa-check-circle"></i> {{__("edit")}}
                            </button>
                            <button class="btn btn-primary" type="submit" form="cancelForm" ><i class="fa fa-fw  fa-window-close"></i> {{__("cancel")}}</button>
                        </div>
                    </form>
                    <form action="{{route("admin.specialization.cancel")}}" method="get" id="cancelForm" class="mt-5">
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section("scripts")





@endsection
