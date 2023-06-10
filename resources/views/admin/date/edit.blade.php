@extends("layouts.admin.app")
@section("page-title")
    {{__("Dashboard")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>{{__("Edit Appointment Detail")}}</h1>

        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("Edit Appointment Details")}}</a></li>
        </ul>
    </div>
@endsection

@section("content")
    @include("includes.dialog")
                <section style="background-color: white;border-radius: 5px">
                    <div class="container py-1 mt-5">
                        <h4 style="margin: 15px">{{__('Edit Booking Info')}}</h4>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card mb-4">
                                    <div class="card-body" style="border: 1px solid #d2d2d2;border-radius: 10px">
                                        <form action="{{route('admin.date.update',$reservation_record_id)}}" method="post">
                                            @csrf
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <p>{{__('Old Booking Info')}}</p>
                                                       <div style="display: flex;flex-wrap: wrap;flex-direction: row">
                                                        <span class="btn btn-danger disabled m-1">{{__('consultant Name')}}: {{$user_consultant ? $user_consultant->full_name : 'NO Name'}}</span>
                                                        <span class="btn btn-danger disabled m-1">{{__('Date')}} : {{$user_object_booking->date ?? 'NO Date'}}</span>
                                                        <span class="btn btn-danger disabled m-1">{{__('Patient Name')}} : {{$user_booking ?? 'NO Name'}}</span>
                                                    </div>
                                                    <div style="display: flex;flex-wrap: wrap;flex-direction: row">
                                                        <span class="btn btn-danger disabled m-1">{{__('Time From')}} : {{date('h:i',strtotime($user_slot_time->time_from))}}</span>
                                                        <span class="btn btn-danger disabled m-1">{{__('Time To')}} : {{date('h:i',strtotime($user_slot_time->time_to))}}</span>
                                                    </div>
                                                    <div style="display: flex;flex-wrap: wrap;flex-direction: row">
                                                        <span class="btn btn-danger disabled m-1 mb-3">{{__('Day')}} : {{\Illuminate\Support\Facades\App::getLocale() == 'en' ?$object_week_days->week_day_en_name : $object_week_days->week_day_ar_name}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                        @livewire('counter',['res_id'=>$reservation_record_id,'user_id'=>$user_id])
                                                    <input type="hidden" value="{{$user_object_booking->object_id}}" name="prev_object_id" />
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
@endsection
@section("scripts")
    <script type="text/javascript" src="{{asset("assets/js/plugins/jquery.dataTables.min.js")}}"></script>
    <script type="text/javascript" src="{{asset("assets/js/plugins/dataTables.bootstrap.min.js")}}"></script>
    <script type="text/javascript">$('#sampleTable').DataTable();</script>
@endsection