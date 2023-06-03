@extends("layouts.admin.app")
@section("page-title")
    {{__("consultations")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
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
    @include("includes.dialog")

    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered text-center" id="sampleTable">
                            <thead>
                            <tr>
                                <th>{{__("user name")}}</th>
                                <th>{{__("consultation name")}}</th>
                                <th>{{__("room")}}</th>
                                <th>{{__("consultations_status")}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($consultations as $consultation)
                                <tr>
                                    <td>{{$consultation->users->full_name}}</td>
                                    <td>{{$consultation->consultant->full_name}}</td>
                                    <td>{{$consultation->room->room_id }}</td>
                                    <td>
                                        @switch($consultation->consultations_status)
                                            @case(1)
                                            <span class="status-box  bg-warning-color">{{__("not read")}}</span>
                                            @break
                                            @case(2)
                                            <span class="status-box bg-active2-color ">{{__("readable")}}</span>
                                            @break
                                            @case(3)
                                            <span class="status-box  bg-primary-color">{{__("follow up ")}}</span>
                                            @break
                                            @case(4)
                                            <span class="status-box  bg-non-active-color">{{__("not important")}}</span>
                                            @break
                                            @case(5)
                                            <span class="status-box  bg-active-color">{{__("completed")}}</span>
                                            @break
                                        @endswitch
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
