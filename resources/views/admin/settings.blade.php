@extends("layouts.admin.app")
@section("page-title")
    {{__("Dashboard")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>{{__("Settings")}}</h1>
            <p>{{__("Settings")}}</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("Settings")}}</a></li>
        </ul>
    </div>
@endsection
@section("css-links")
    <link rel="stylesheet" href="{{asset("assets/css/" . app()->getLocale() . "/pages/settings.css")}}">
@endsection

@section("content")
    @include("includes.dialog")

    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <form action="{{route("admin.settings.save")}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row settings-group">
                            <div class="col-lg-4 settings-section">
                                <h3 class="text-center"><span class="d-inline-block border-bottom pb-2 pl-3 pr-3">Payment Methods</span></h3>
                                <div class="row mt-5">
                                    @foreach($paymentMethods as $method)
                                        <div class="col-lg-4 text-center">
                                            <div class="form-group payment-method-box">
                                                <div class="upload-payment-method-icon">
                                                    <button class=" form-control button-upload-file" >
                                                        <input class="input-file show-uploaded" data-upload-type="single" data-imgs-container-class="uploaded-images" type="file" name="payment-method-photo-{{$method->id}}">
                                                        <span class="upload-file-content">
                                                            <i class="fas fa-upload fa-lg upload-file-content-icon left"></i>
                                                        </span>
                                                    </button>
                                                </div>
                                                <div>
                                                    <img src="{{$method->getFirstMediaFile() ? $method->getFirstMediaFile()-> url : ''}}" alt="">
                                                </div>

                                                <label for="" class="text-size-16 font-weight-bold d-inline-block mb-3">{{$method->name}}</label>
                                                <input type="hidden" name="payment_methods[]" value="{{$method->id}}">
                                                <div class="toggle-flip">
                                                    <label>
                                                        <input type="checkbox" class="checked-action" name="payment-method-{{$method->id}}" {{checked("active", 1, $method)}}/><span class="flip-indecator" data-toggle-on="{{__('ON')}}" data-toggle-off="{{__('OFF')}}" ></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="tile-footer">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>{{(__("Save"))}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section("scripts")



@endsection
