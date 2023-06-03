@extends("layouts.admin.app")

@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> {{__("Categories")}}</h1>
            <p>{{__("Edit category")}}</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="{{route("admin.categories.index")}}">{{__("Categories")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("Edit")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{$category->name}}</a></li>
        </ul>
    </div>
@endsection

@section("content")
    <div class="row">
        <div class="col-lg-10 m-auto">
            <div class="tile">
                <h3 class="tile-title">{{__("Edit Category")}}</h3>
                <div class="tile-body">
                    <form method="post" action="{{route("admin.categories.update", $category->id)}}" enctype="multipart/form-data">
                        @csrf
                        @method("put")
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("English Category Name")}}</label>
                                    <input class="form-control @if($errors->has('name_en')) is-invalid @endif" type="text" name="name_en" placeholder="{{__("Enter English Category name")}}" value="{{inputValue("name_en",$category)}}">
                                </div>
                                @error("name_en")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Arabic Category Name")}}</label>
                                    <input class="form-control @if($errors->has('name_ar')) is-invalid @endif" type="text" name="name_ar" placeholder="{{__("Enter Arabic Category name")}}" value="{{inputValue("name_ar",$category)}}">
                                </div>
                                @error("name_ar")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label class="control-label">{{__("Category Status")}}</label>
                                <div class="toggle-flip">
                                    <label>
                                        <input type="checkbox" name="category_status" {{checked("status", 1, $category)}}><span class="flip-indecator" data-toggle-on="{{__("ON")}}" data-toggle-off="{{__("OFF")}}"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Category Photo")}}</label>
                                    <div>
                                        <button class="btn btn-primary form-control button-upload-file" >
                                            <input class="input-file show-uploaded" data-upload-type="single" data-imgs-container-class="uploaded-images" type="file" name="category_photo">
                                            <span class="upload-file-content">
                                                <i class="fas fa-upload fa-lg upload-file-content-icon left"></i>
                                                <span class="upload-file-content-text">{{__("Upload Photo")}}</span>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                                @error("category_photo")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-3 col-md-5 col-sm-6">
                                <div class="uploaded-images" style="margin-bottom: 20px;">
                                    <div class="img-container">
                                        <img src="{{ $category->getFirstMediaFile()->url }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tile-footer">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>{{__("Save")}}</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
@section("scripts")


@endsection
