@extends("layouts.admin.app")

@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> {{__("Brands")}}</h1>
            <p>{{__("Edit Brand")}}</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="{{route("admin.brands.index")}}">{{__("Brands")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("Edit")}}</a></li>
        </ul>
    </div>
@endsection
@section("content")
    <div class="row">
        <div class="col-lg-10 m-auto">
            <div class="tile">
                <h3 class="tile-title">{{__("Edit Item")}}</h3>
                <div class="tile-body">
                    <form method="post" action="{{route("admin.brands.update", $brand->id)}}" enctype="multipart/form-data">
                        @csrf
                        @method("put")
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("English Brand Name")}}</label>
                                    <input class="form-control @if($errors->has('name_en')) is-invalid @endif" type="text" name="name_en" placeholder="Enter English Brand name" value="{{inputValue("name_en", $brand)}}">
                                </div>
                                @error("name_en")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Arabic Brand Name")}}</label>
                                    <input class="form-control @if($errors->has('name_ar')) is-invalid @endif" type="text" name="name_ar" placeholder="Enter Arabic Brand name" value="{{inputValue("name_ar", $brand)}}">
                                </div>
                                @error("name_ar")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="exampleSelect1">{{__("Select Category")}}</label>
                                    <select class="form-control" id="exampleSelect1" name="category_id">
                                        <option value="">{{__("None")}}</option>
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}" {{selected("category_id", $category->id, $brand)}}>{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error("category_id")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Brand English Description")}}</label>
                                    <textarea class="form-control @if($errors->has('description_en')) is-invalid @endif" name="description_en" cols="30" rows="10" placeholder="{{__("Enter English Description")}}">{{inputValue("description_en", $brand)}}</textarea>
                                </div>
                                @error("description_en")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Brand Arabic Description")}}</label>
                                    <textarea class="form-control @if($errors->has('description_ar')) is-invalid @endif" name="description_ar" cols="30" rows="10" placeholder="{{__("Enter Arabic Description")}}">{{inputValue("description_ar", $brand)}}</textarea>
                                </div>
                                @error("description_ar")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Brand Photo")}}</label>
                                    <div>
                                        <button class="btn btn-primary form-control button-upload-file" >
                                            <input class="input-file show-uploaded" data-upload-type="single" data-imgs-container-class="uploaded-images" type="file" name="image">
                                            <span class="upload-file-content">
                                            <i class="fas fa-upload fa-lg upload-file-content-icon left"></i>
                                            <span class="upload-file-content-text">{{__("Upload Photo")}}</span>
                                        </span>
                                        </button>
                                    </div>
                                </div>
                                @error("image")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-3 col-md-5 col-sm-6">
                                <div class="uploaded-images" >
                                    <div class="img-container">
                                        <img src="{{ $brand->getFirstMediaFile()->url }}" alt="">
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
