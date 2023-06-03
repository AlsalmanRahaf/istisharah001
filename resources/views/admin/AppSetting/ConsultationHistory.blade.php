@extends("layouts.admin.app")
@section("page-title")
    {{__("App Setting main button")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>     {{__("App Setting main button")}}</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("App Setting ")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("main button")}}</a></li>
        </ul>
    </div>
@endsection
@section("content")
    <div class="col-md-12">
        <div class="tile">
            @foreach($Buttons as $Button)
                <form method="post" action="{{route("admin.App-Setting.UpdateButton",["id"=>$Button->id,"redirect"=>"admin.App-Setting.ConsultationHistory"])}}" enctype="multipart/form-data">
                    @csrf
                    @method("put")
                    <div class="offset-3 col-6 tile-body btn btn-primary mb-5 border border-white" style="border-radius: 12px">
                        <div class="row  m-5 ">
                            <div class="offset-2 row">
                                <div class=" col-md-6 ">
                                    <div class="form-group">
                                        <label class="control-label">Title En</label>
                                        <input class="form-control " type="text" name="title_en" placeholder="{{$Button->title_en}}">
                                    </div>
                                </div>

                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label class="control-label">Title Ar</label>
                                        <input class="form-control " type="text" name="title_ar" placeholder="{{$Button->title_ar}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            @endforeach
        </div>
    </div>
@endsection
@section("scripts")
    <script type="text/javascript" src="{{asset('assets/js/plugins/dataTables.bootstrap.min.js')}}"></script>
@endsection
