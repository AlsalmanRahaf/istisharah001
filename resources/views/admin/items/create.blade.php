@extends("layouts.admin.app")

@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> {{__("Items")}}</h1>
            <p>{{__("Create new item")}}</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="{{route("admin.items.index")}}">{{__("Items")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("Create")}}</a></li>
        </ul>
    </div>
@endsection
@section("content")
    <div class="row">
        <div class="col-lg-12 m-auto">
            <div class="tile">
                <h3 class="tile-title">{{__("Create New Item")}}</h3>
                <div class="tile-body">
                    <form method="post" action="{{route("admin.items.store")}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("English Item Name")}}</label>
                                    <input class="form-control @if($errors->has('name_en')) is-invalid @endif" type="text" name="name_en" placeholder="{{__("Enter English Item name")}}" value="{{inputValue("name_en")}}">
                                </div>
                                @error("name_en")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Arabic Item Name")}}</label>
                                    <input class="form-control @if($errors->has('name_ar')) is-invalid @endif" type="text" name="name_ar" placeholder="{{__("Enter Arabic Item name")}}" value="{{inputValue("name_ar")}}">
                                </div>
                                @error("name_ar")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="exampleSelect1">{{__("Category")}}</label>
                                    <select class="form-control @if($errors->has('category_id')) is-invalid @endif" id="exampleSelect1" name="category_id">
                                        <option value="">{{__("None")}}</option>
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}" {{selected("category_id", $category->id)}}>{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error("category_id")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Price")}}</label>
                                    <input class="form-control @if($errors->has('price')) is-invalid @endif" type="text" name="price" placeholder="{{__("Enter Item Price")}}" value="{{inputValue("price")}}">
                                </div>
                                @error("price")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>

                            <div class="col-lg-6" >
                                <div class="form-group">
                                    <label class="control-label">{{__("Quantity")}}</label>
                                    <input class="form-control @if($errors->has('quantity')) is-invalid @endif" type="text" name="quantity" placeholder="{{__("Enter Item quantity")}}" value="{{inputValue("quantity")}}">
                                </div>
                                @error("quantity")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>


                            <div class="col-lg-6" >
                                <div class="form-group">
                                    <label class="control-label">{{__("Item Tax")}}</label>
                                    <input class="form-control @if($errors->has('tax')) is-invalid @endif" type="text" name="tax" placeholder="{{__("Enter Item Tax")}}" value="{{inputValue("tax")}}">
                                </div>
                                @error("tax")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>


                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("English Item Description")}}</label>
                                    <textarea class="form-control @if($errors->has('description_en')) is-invalid @endif" type="text" name="description_en" cols="30" rows="10" placeholder="{{__("Enter English Description")}}">{{inputValue("description_en")}}</textarea>
                                </div>
                                @error("description_en")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Arabic Item Description")}}</label>
                                    <textarea class="form-control @if($errors->has('description_ar')) is-invalid @endif" type="text" name="description_ar" cols="30" rows="10" placeholder="{{__("Enter Arabic Description")}}" >{{inputValue("description_ar")}}</textarea>
                                </div>
                                @error("description_ar")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="exampleSelect1">{{__("Branches")}}</label>
                                    <select class="form-control @if($errors->has('branches')) is-invalid @endif" id="demoSelect" multiple="" name="branches[]">
                                        <optgroup label="Select Branches">
                                            @foreach($branches as $branch)
                                                <option value="{{$branch->id}}" >{{$branch->store_name}}</option>
                                            @endforeach
                                        </optgroup>

                                    </select>
                                </div>

                                @error("branches_ids")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <hr>

                        <div id="ColorInput">
                            <div class="row option-size-box">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label">{{__("Image Color")}}</label>
                                        <div>
                                            <button class="btn btn-primary form-control button-upload-file" >
                                                <input class="input-file show-uploaded" data-upload-type="single" multiple data-imgs-container-class="uploaded-images-3" type="file" name="image_color[]">
                                                <span class="upload-file-content">
                                                    <i class="fas fa-upload fa-lg upload-file-content-icon left"></i>
                                                    <span class="upload-file-content-text">{{__("Upload Photo")}}</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-5 col-sm-6">
                                    <div class="uploaded-images-3 all-uploaded" ></div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-username"> {{__("Color")}}</label>
                                        <input type="color"  name="colors[]" class="form-control" placeholder="color">
                                    </div>
                                </div>
                                <div class="col-lg-6 text-center ">
                                    <span class="addColor btn btn-success btn-sm ">
                                        <i class="fa fa-plus"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div id="SizeInput">
                            <div class="row option-size-box">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-username"> {{__("Size")}}</label>
                                        <input type="text"  name="sizes[]" class="form-control" placeholder="Size">
                                    </div>
                                </div>
                                <div class="col-lg-6 text-center">
                                    <span class="addSize btn btn-success btn-sm ">
                                        <i class="fa fa-plus"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Item Photo")}}</label>
                                    <div>
                                        <button class="btn btn-primary form-control button-upload-file" >
                                            <input class="input-file show-uploaded" data-upload-type="single" data-imgs-container-class="uploaded-images" type="file" name="item_image">
                                            <span class="upload-file-content">
                                                <i class="fas fa-upload fa-lg upload-file-content-icon left"></i>
                                                <span class="upload-file-content-text">{{__("Upload Photo")}}</span>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                                @error("item_image")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-3 col-md-5 col-sm-6">
                                <div class="uploaded-images" ></div>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Gallery item Photo")}}</label>
                                    <div>
                                        <button class="btn btn-primary form-control button-upload-file" >
                                            <input class="input-file show-uploaded" data-upload-type="multi" multiple data-imgs-container-class="uploaded-images-1" type="file" name="gallery[]">
                                            <span class="upload-file-content">
                                                <i class="fas fa-upload fa-lg upload-file-content-icon left"></i>
                                                <span class="upload-file-content-text">{{__("Upload Photo")}}</span>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                                @error("gallery")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-3 col-md-5 col-sm-6">
                                <div class="uploaded-images-1 all-uploaded" ></div>
                            </div>

                        </div>


                        <div class="tile-footer">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>{{__("Create")}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section("scripts")
    <script type="text/javascript" src="{{asset("assets/js/plugins/select2.min.js")}}"></script>
    <script type="text/javascript">
        $('#demoSelect').select2();
    </script>
    <script type="module" src="{{asset("assets/js/pages/item.js")}}"></script>


@endsection
@include("admin.items.template")
