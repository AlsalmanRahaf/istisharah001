@extends("layouts.admin.app")
@section("page-title")
    {{__("Dashboard")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>{{__("notification-reminder")}}</h1>

        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("notification-reminder")}}</a></li>
        </ul>
    </div>
@endsection

@section("content")
    @include("includes.dialog")

    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered text-center" id="sampleTable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>{{__("duration")}}</th>
                                <th>{{__("Control")}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $serial_number = 1; ?>
                            @foreach($durations as $duration)
                                <tr>
                                    <td>{{$serial_number++}}</td>
                                    <td>{{$duration->duration_number . " " . $duration->duration_type	}}</td>
                                    <td>
                                        <a href="{{route("admin.notification-reminder.edit",$duration->id)}}" class="control-link edit"><i class="fas fa-edit"></i></a>
                                        <form action="{{route("admin.notification-reminder.destroy", ["id" => $duration->id])}}" method="post" id="delete{{$duration->id}}" style="display: none" data-swal-title="{{__("Delete")}}" data-swal-text="{{__("Are Your Sure To Delete This Data ?")}}" data-yes="{{__("Yes")}}" data-no="{{__("No")}}" data-success-msg="{{__('the Data has been deleted successfully')}}">@csrf @method("delete")</form>
                                        <span href="#" class="control-link remove form-confirm" data-form-id="#delete{{$duration->id}}"><i class="far fa-trash-alt"></i></span>
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
