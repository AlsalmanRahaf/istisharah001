@extends("layouts.admin.app")

@section("page-title")
    Dashboard
@endSection

@section("page-nav-title")
    <div class="app-title">
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
            <div>
                <h1>{{__("All Consultations")}}</h1>
            </div>

            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
            </ul>
        </div>
@endsection
@section("content")
    <div class="row">
        <div class="col-lg-10 m-auto">
            <div class="tile">
                <h3 class="tile-title">{{__("Create consultation")}}</h3>
                @include("includes.dialog")
                <div class="tile-body">
                    <form method="post" action="{{route("admin.consultations.store")}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="exampleSelect1">{{__("select User")}}</label>
                                    <div class="col-lg-12">
                                        <div class="table-responsive" id="form_status">
                                            <table class="table table-hover table-bordered text-center" id="sampleTable">
                                                <thead>
                                                <tr>
                                                    <th>{{__("Select")}}</th>
                                                    <th>{{__("Phone Number")}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($users as $user)
                                                    <tr>
                                                        <td><input type="checkbox" class="form-control"  name="user_ids[]" value="{{$user->id}}"></td>
                                                        <td>{{$user->full_name != null ?$user->full_name:$user->phone_number}}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                            @error("user_ids")
                                            <div class="input-error text-danger">must select one at least</div>
                                            @enderror
                                        </div>
                                    </div>


                                </div>
                                @error("user")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="control-label @if($errors->has('consultation_name_en')) is-invalid @endif">{{__("Consultation Name EN")}}</label>
                                    <input class="form-control @if($errors->has('consultation_name_en')) is-invalid @endif" type="text" name="consultation_name_en" placeholder="{{__("Enter consultation name")}}" value="{{old('consultation_name')}}">
                                </div>
                                @error("consultation_name_en")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="control-label @if($errors->has('consultation_name_ar')) is-invalid @endif ">{{__("Consultation Name Ar")}}</label>
                                    <input class="form-control @if($errors->has('consultation_name_ar')) is-invalid @endif" type="text" name="consultation_name_ar" placeholder="{{__("Enter consultation name ")}}" value="{{old('consultation_name_ar')}}">
                                </div>
                                @error("consultation_name_ar")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <hr>
                        <div class="tile-footer">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Create</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
@section("scripts")
    <!-- Data table plugin-->
    <script type="text/javascript" src="{{asset("assets/js/plugins/jquery.dataTables.min.js")}}"></script>
    <script type="text/javascript" src="{{asset("assets/js/plugins/dataTables.bootstrap.min.js")}}"></script>
    <script type="text/javascript">$('#sampleTable').DataTable();</script>
@endsection
