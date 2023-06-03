@extends("layouts.admin.app")
@section("page-title")
    {{__("Dashboard")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1> {{__("doctors")}}</h1>
            <p>{{__("edit-doctor")}}</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="{{route("admin.doctor.index")}}">{{__("doctors")}}</a></li>
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
                <h3 class="tile-title">{{__("edit-doctor")}}</h3>
                <div class="tile-body">
                    <form method="post" action="{{route("admin.doctor.update", $doctor->id)}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("full-name")}}</label>
                                    <input class="form-control @if($errors->has('full_name')) is-invalid @endif" type="text" name="full_name" placeholder="{{__("enter-full-name")}}" value="{{$doctor->full_name}}">
                                </div>
                                @error("full_name")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("phone-number")}}</label>
                                    <input class="form-control @if($errors->has('phone_number')) is-invalid @endif" type="text" name="phone_number" placeholder="{{__("enter-phone-number")}}" value="{{$doctor->phone_number}}">
                                </div>
                                @error("phone_number")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("email")}}</label>
                                    <input class="form-control @if($errors->has('email')) is-invalid @endif" type="email" name="email" placeholder="{{__("enter-email")}}" value="{{$doctor->email}}">
                                </div>
                                @error("email")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("specialization")}}</label>
                                    <select class="form-control @if($errors->has('specialization')) is-invalid @endif" id="specialization" name="specialization">
                                        <option></option>
                                        @foreach($specializations as $specialization)
                                            <option value="{{$specialization->id}}" @if($doctor->specialization_id === $specialization->id) selected @endif>{{$specialization->name_en}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error("specialization")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="checkbox-correct size-1 check-box mr-2" style="padding-top: 33px">
                                        <label class="control-label" style="display:flex"><input type="checkbox" @if($doctor->has_zoom == 1) checked @endif id="has_zoom" name="has_zoom" style="@if(app()->getLocale() == 'en') margin-right:10px @else margin-left:10px @endif">{{__("zoom-meeting-availability")}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("user")}}</label>
                                    <select class="form-control @if($errors->has('user_id')) is-invalid @endif" id="user_id" name="user_id">
                                        <option></option>
                                        @foreach($users as $user)
                                            <option value="{{$user->id}}"@if($doctor->user_id === $user->id) selected @endif>{{$user->phone_number}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error("user_id")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="control-label">{{__("about-doctor")}}</label>
                                    <textarea style="height: 40px" class="form-control @if($errors->has('doctor_description')) is-invalid @endif" type="text" name="doctor_description" placeholder="{{__("enter-description")}}">{{$doctor->description}}</textarea>
                                </div>
                            </div>
                        </div>
                        <label class="control-label required" style="padding-top: 10px">{{__("payment-methods")}}</label>
                        <div class="row" style="padding-top: 10px">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <div class="checkbox-correct size-1 check-box mr-2">
                                        <label class="control-label" style="display:flex"><input type="checkbox" @if($doctor->cash == 1) checked @endif id="cash" name="payment[]" value="cash" style="@if(app()->getLocale() == 'en') margin-right:10px @else margin-left:10px @endif">{{__("cash")}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <div class="checkbox-correct size-1 check-box mr-2">
                                        <label class="control-label" style="display:flex"><input type="checkbox" @if($doctor->online == 1) checked @endif id="online" name="payment[]" value="online" style="@if(app()->getLocale() == 'en') margin-right:10px @else margin-left:10px @endif">{{__("online")}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @error("payment")
                        <div class="input-error">{{$message}}</div>
                        @enderror
                        <hr>
                        <h3>{{__('booking-info')}}</h3>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label required">{{__("time-slot-type")}}</label>
                                    <select class="form-control @if($errors->has('time_slot_type')) is-invalid @endif" id="time_slot_type" name="time_slot_type">
                                        <option></option>
                                        @foreach($timeSlotTypes as $type)
                                            <option value="{{$type->id}}" @if($objectDetails->time_slot_type_id === $type->id) selected @endif>{{$type->description}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error("time_slot_type")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label required">{{__("description")}}</label>
                                    <textarea class="form-control @if($errors->has('object_description')) is-invalid @endif" name="object_description" placeholder="{{__("enter-description")}}"  value="{{$objectDetails->description}}">{{$objectDetails->description}}</textarea>
                                </div>
                                @error("object_description")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("doctor-photo")}}</label>
                                    <div>
                                        <button class="btn btn-primary form-control button-upload-file" >
                                            <input class="input-file show-uploaded" data-upload-type="single" data-imgs-container-class="uploaded-images" type="file" name="doctor_image">
                                            <span class="upload-file-content">
                                                <i class="fas fa-upload fa-lg upload-file-content-icon left"></i>
                                                <span class="upload-file-content-text">{{__("Upload Photo")}}</span>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                                @error("doctor_image")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-5 col-sm-6">
                            <div class="images">
                                    <div class="uploaded-images">
                                        <img src="{{$doctor->getFirstMediaFile("Doctors")->url}}" alt="" width="90px">
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
                    <form action="{{route("admin.doctor.cancel")}}" method="get" id="cancelForm" class="mt-5">
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section("scripts")
    <script type="text/javascript">
        $("#specialization").select2({
            placeholder: "{{__('select-specialization')}}",
            width: "100%",
        });
        $("#object").select2({
            placeholder: "{{__('select-clinic')}}",
            width: "100%",
        });
        $("#time_slot_type").select2({
            placeholder: "{{__('select-time-slot-type')}}",
            width: "100%",
        });
        $("#user_id").select2({
            placeholder: "{{__('select-user')}}",
            width: "100%",
        });
    </script>

@endsection
