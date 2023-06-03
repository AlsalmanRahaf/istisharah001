@extends("layouts.admin.app")
@section("page-title")
    {{__("Dashboard")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1>{{__("Service General Question")}}</h1>
            <p>{{__("All Service General Question")}}</p>
        </div>

        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="{{route("admin.dashboard.index")}}">{{__("Dashboard")}}</a></li>
        </ul>
    </div>
@endsection

@section("content")
    @include("includes.dialog")

    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="table-responsive" id="form_status">
                        <table class="table table-hover table-bordered text-center" id="sampleTable">
                            <thead>
                            <tr>
                                <th>{{__("image")}}</th>
                                <th>{{__("status")}}</th>
                                <th>{{__("Control")}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($sliders as $slider)
                                <tr>
                                    <td><img  src="{{$slider->url}}" width="150px"></td>
                                    <td class="text-center">
                                        <div class="form-group">
                                            <div class="toggle-flip changes-status">
                                                <label>
                                                    <input  type="checkbox" class="change-status" name="status" data-url="{{ route("ajax.Slider.update")}}" data-id="{{$slider->id}}" data-status="{{$slider->status}}" {{ checked("status", 1, $slider)}}><span class="flip-indecator" data-toggle-on="{{__("ON")}}" data-toggle-off="{{__("OFF")}}"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{route("admin.sliders.edit", $slider->id)}}" class="control-link edit"><i class="fas fa-edit"></i></a>
                                        <form action="{{route("admin.sliders.destroy", $slider->id)}}" method="post" id="delete{{$slider->id}}" style="display: none" data-swal-title="Delete Property" data-swal-text="{{__("Are Your Sure To Delete This Property ?")}}" data-yes ="{{__("Yes")}}" data-no="{{__("No")}}" data-success-msg="{{__("the Slider has been deleted successfully")}}">@csrf @method("delete")</form>
                                        <span href="#" class="control-link remove form-confirm" data-form-id="#delete{{$slider->id}}"><i class="far fa-trash-alt"></i></span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
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
