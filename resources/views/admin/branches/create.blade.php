@extends("layouts.admin.app")

@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> {{__("Branches")}}</h1>
            <p>{{__("Control and view all Branches of Store")}}</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="{{route("admin.branches.index")}}">{{__("Branches")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("Create")}}</a></li>
        </ul>
    </div>
@endsection
@section("content")
    <div class="row">
        <div class="col-lg-10 m-auto">
            <div class="tile">
                <h3 class="tile-title">{{__("Create New Branch")}}</h3>
                <div class="tile-body">
                    <form method="post" action="{{route("admin.branches.store")}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Branch name")}}</label>
                                    <input class="form-control @if($errors->has('store_name')) is-invalid @endif" type="text" name="store_name" placeholder="{{__("Branch name")}}" value="{{inputValue("store_name")}}">
                                </div>
                                @error("store_name")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Phone number")}}</label>
                                    <input class="form-control @if($errors->has('phone_number')) is-invalid @endif" type="text" name="phone_number" placeholder="{{__("example : 0791234567")}}" value="{{inputValue("phone_number")}}">
                                </div>
                                @error("phone_number")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="exampleSelect1">{{__("Select Category")}}</label>
                                    <select class="form-control" id="exampleSelect1" name="category_id">
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
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Branch Photo")}}</label>
                                    <div>
                                        <button class="btn btn-primary form-control button-upload-file" >
                                            <input class="input-file show-uploaded" data-upload-type="single" data-imgs-container-class="uploaded-images"  type="file" name="img">
                                            <span class="upload-file-content">
                                            <i class="fas fa-upload fa-lg upload-file-content-icon left"></i>
                                            <span class="upload-file-content-text">{{__("Upload Image")}}</span>
                                        </span>
                                        </button>
                                    </div>
                                </div>
                                @error("img")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">

                                    <label class="control-label">{{__("Location")}}</label>
                                    <div>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#openMap">
                                            Locate on the Map
                                        </button>
                                        <div class="modal fade" id="openMap" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">{{__("Locate on The Map")}}</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div id="map"></div>
                                                        <input type="hidden" name="latitude" id="lat" value="{{inputValue("latitude")}}">
                                                        <input type="hidden" name="longitude" id="lng" value="{{inputValue("longitude")}}">
                                                        <input type="hidden" name="address" id="address" value="{{inputValue("address")}}">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-primary" data-dismiss="modal">{{__("Done")}}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @error("location")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-3 col-md-5 col-sm-6">
                                <div class="uploaded-images">
                                </div>
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
    <script src="{{asset("assets/js/maps.js")}}"></script>
    <script type="text/javascript">
        if(document.location.hostname == 'pratikborsadiya.in') {
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
            ga('create', 'UA-72504830-1', 'auto');
            ga('send', 'pageview');
        }
    </script>
    <script
            src="https://maps.googleapis.com/maps/api/js?key={{env("GOOGLE_API_KEY")}}&callback=initMap&libraries=&v=weekly"
            async
    ></script>
@endsection
