@extends("layouts.admin.app")
@section("page-title")
    {{__("Dashboard")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>{{__("Time Slot Types")}}</h1>

        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("All Time Slot Types")}}</a></li>
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
                                <th>{{__("#ID")}}</th>
                                <th>{{__("Description")}}</th>
                                <th>{{__("Slot Duration")}}</th>
                                <th>{{__("Time From")}}</th>
                                <th>{{__("Time To")}}</th>
                                <th>{{__("Details")}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($time_slot_type as $index => $type)
                            <tr>
                                <td>{{++$index}}</td>
                                <td>{{$type->description}}</td>
                                <td>{{$type->slot_duration}}</td>
                                <td>{{date('h:i',strtotime($type->time_from))}}</td>
                                <td>{{date('h:i',strtotime($type->time_to))}}</td>
                                <td>
                                    <a href="{{route('admin.timeSlotType.details',$type->id)}}"  class="btn btn-danger">
                                        {{__("Details")}}
                                    </a>
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