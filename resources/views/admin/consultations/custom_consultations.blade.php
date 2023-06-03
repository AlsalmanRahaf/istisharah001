@extends("layouts.admin.app")
@section("page-title")
    {{__("consultations")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1>{{__("Other Consultations")}}</h1>
        </div>
        <a class="btn btn-primary" style="float: right;" href="{{route('admin.consultations.Create')}}"> {{__('Create consultation type')}}</a>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
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
                                <th>{{__("ID")}}</th>
                                <th>{{__("consultation Type")}}</th>
                                <th>{{__("consultant name")}}</th>
                                <th>{{__("consultant phone")}}</th>
                                <th>{{__("Status")}}</th>
                                <th>{{__("action")}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($consultations as $consultation)
                                <tr>
                                    <td>{{$consultation->id}}</td>
                                    <td>{{$consultation->consultation_name}}</td>
                                    <td>{{$consultation->consultant->full_name}}</td>
                                    <td>{{$consultation->consultant->phone_number}}</td>
                                    <td class="text-center">
                                        <div class="form-group">
                                            <div class="toggle-flip changes-status">
                                                <label>
                                                    <input  type="checkbox" class="change-status" name="status" data-url="{{ route("ajax.other.update")}}" data-id="{{$consultation->id}}" data-status="{{$consultation->status}}" {{ checked("status", 1, $consultation)}}><span class="flip-indecator" data-toggle-on="{{__("ON")}}" data-toggle-off="{{__("OFF")}}"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <form action="{{route("admin.consultations.destroy", ["id"=> $consultation->id])}}" method="post" id="delete{{$consultation->id}}" style="display: none" data-swal-title="Delete Property" data-swal-text="{{__("Are Your Sure To Delete ?")}}" data-yes ="{{__("Yes")}}" data-no="{{__("No")}}" data-success-msg="{{__("the consultant has been deleted successfully")}}">@csrf @method("delete")</form>
                                        <span href="#" class="control-link remove form-confirm" data-form-id="#delete{{$consultation->id}}"><i class="far fa-trash-alt"></i></span>
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
