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
                    <div class="table-responsive" style="overflow-x: hidden">
                        <div class="row">
                            @foreach($time_slot_type_details->object_week_days as $details)
                            <div class="col-sm-4">
                                <div class="card" style="display: flex;overflow-x: hidden;border-radius: 7px;border: 1px solid #bdbdbd">
                                    <div class="card-body">
                                        <h5 class="card-title btn btn-danger disabled">{{__('Day')}} : {{\Illuminate\Support\Facades\App::getLocale() == 'en' ? $details->week_day_en_name : $details->week_day_ar_name}}</h5>
                                        <p class="card-title btn btn-danger disabled">{{__('Is Off')}} : {{$details->is_off == '0' ? (\Illuminate\Support\Facades\App::getLocale() == 'en' ? 'No' : 'لا') : (\Illuminate\Support\Facades\App::getLocale() == 'en' ? 'Yes' : 'نعم')}}</p>
                                        <p class="card-title btn btn-danger disabled">{{__('Week Day Number')}} : {{$details->week_day_number}}</p>
                                        <div style="    border-radius: 7px;border: 1px solid #bdbdbd;padding: 10px">
                                            @foreach($details->time_slot as $time_slot)
                                                <p class="card-text"><span style="font-weight: bolder">{{__('Time From')}}</span> : {{date('h:i',strtotime($time_slot->time_from))}} / <span style="font-weight: bolder">{{__('Time To')}}</span> : {{date('h:i',strtotime($time_slot->time_to))}}</p>
                                           @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection