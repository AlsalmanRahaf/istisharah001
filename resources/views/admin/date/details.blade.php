@extends("layouts.admin.app")
@section("page-title")
    {{__("Dashboard")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>{{__("Appointment Details")}}</h1>

        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("Appointment Details")}}</a></li>
        </ul>
    </div>
@endsection

@section("content")
    @include("includes.dialog")
    <div>
        <div class="row">

    @if($users_booking)
         @if($users_booking->is_cancelled == 1)
             @php
               $cancelled = \App\Models\CancelledBooking::where('booking_id',$users_booking->reservation_record_id)->first();
               if($cancelled){
               $userCancelled = \App\Models\User::where('id',$cancelled->cancelled_by)->first();
               }
             @endphp
             @if($cancelled)
                        <div class="col-lg-6">
                            <h4 style="margin-top: 5px">{{__('Cancelled')}}</h4>

                            <div class="card mb-4">
                            <div class="card-body" style="border: 1px solid #d2d2d2">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">{{__('Cancellation Reason')}}</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">{{$cancelled->cancellation_reason}}</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">{{__('Cancellation Date')}}</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">{{$cancelled->cancellation_date}}</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">{{__('Cancellation Time')}}</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">{{date('h:i',strtotime($cancelled->cancellation_time))}}</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">{{__('Cancelled By')}}</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">{{auth()->user()->full_name}}</p>
                                    </div>
                                </div>
                                <hr>
                            </div>
                        </div>
                    </div>
             @endif
         @endif
            @php
                $slot_time = App\Models\TimeSlot::where('id',$users_booking->slot_id)->first();
                $url = App\Models\OnlineBooking::where('booking_id',$users_booking->reservation_record_id)->first();
            @endphp
             <div class="col-lg-6">
                        <h4 style="margin-top: 5px">{{__('Details')}}</h4>
                        <div class="card">
                            <div class="card-body" style="border: 1px solid #d2d2d2">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">{{__('Time From')}}</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">{{date('h:i',strtotime($slot_time->time_from))}}</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">{{__('Time To')}}</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">{{date('h:i',strtotime($slot_time->time_to))}}</p>
                                    </div>
                                </div>
                                <hr>
                                @php
                                    $mytime = \Carbon\Carbon::now();
                                    $currentDate = $mytime->toDateString();
                                @endphp
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">{{__('Status')}}</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">{{\Illuminate\Support\Facades\App::getLocale() == 'en' ? ($currentDate <= $users_booking->date? "Coming" : "Expired") : ($currentDate <= $users_booking->date? "قادم" : "منتهي")}}</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">{{__('Booking Date')}}</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">{{$users_booking->date}}</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">{{__('Reservation Record Id')}}</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">{{$users_booking->reservation_record_id}}</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">{{__('Type')}}</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">{{$users_booking->is_online == 1 ? 'Online' : 'Direct'}}</p>
                                    </div>
                                </div>
                                <hr>
                                    @if($users_booking->is_online === 1 && $users_booking->is_cancelled == 0)
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <p class="mb-0">{{__('Zoom Url')}}</p>
                                            </div>
                                            <div class="col-sm-9">
                                                <p class="text-muted mb-0">{{$url->zoom_url ?? null}}</p>
                                            </div>
                                        </div>
                                    <hr>
                                @endif
                            </div>
                        </div></div>
             <div class="col-lg-6">
                <h4 style="margin-top: 5px">{{__('Doctor')}}</h4>
                <div class="row" >
                 <div class="col-lg-12" >
                     <div class="card mb-1" style="border: 1px solid #d2d2d2">
                         <div class="card-body text-center">
                             @if($consultant)
                                 <img src="{{$consultant->getFirstMediaFile('Doctors') ? $consultant->getFirstMediaFile('Doctors')->url : 'https://dashboard.medical001.digisolapps.com//assets/img/user_avatar.jpg'}}" alt="avatar"
                                      class="rounded-circle img-fluid" style="width:110px;height:100px"/>
                             @endif
                             <h5 class="my-3">{{$users_booking->full_name}}</h5>
                             <p class="text-muted mb-1">{{$users_booking->email}}</p>
                         </div>
                     </div>
                     <div class="card" style="border: 1px solid #d2d2d2">
                         <div class="card-body">
                             <div class="row">
                                 <div class="col-sm-3">
                                     <p class="mb-0">{{__('Phone')}}</p>
                                 </div>
                                 <div class="col-sm-9">
                                     <p class="text-muted mb-0">{{$users_booking->phone_number}}</p>
                                 </div>
                             </div>
                             <hr>
                             @php
                                 $slot_time = App\Models\ObjectDetails::where('id',$users_booking->object_id)->first();
                             @endphp
                             @if($slot_time)
                                 <div class="row">
                                     <div class="col-sm-3">
                                         <p class="mb-0">{{__('Doctor Time Slot')}}</p>
                                     </div>
                                     <div class="col-sm-9">
                                         <p class="text-muted mb-0">{{$slot_time->time_slot_types->description ? $slot_time->time_slot_types->description : "No Data"}}</p>
                                     </div>
                                 </div>
                             @endif
                             <hr>
                             @php
                                 if ($consultant){
                                     $Specializations = \App\Models\Specialization::where('id',$consultant->specialization_id)->first();
                                 }
                             @endphp
                             <div class="row">
                                 <div class="col-sm-3">
                                     <p class="mb-0">{{__('Specializations')}}</p>
                                 </div>
                                 <div class="col-sm-9">
                                     <p class="text-muted mb-0">{{ $Specializations ? ($Specializations->name_.\Illuminate\Support\Facades\App::getLocale() == 'en' ? $Specializations->name_en : $Specializations->name_ar )  : "The Doctor has no specialization"}}</p>
                                 </div>
                             </div>
                             <hr>
                             <div class="row">
                                 <div class="col-sm-3">
                                     <p class="mb-0">{{__('Has Zoom')}}</p>
                                 </div>
                                 <div class="col-sm-9">
                                     <p class="text-muted mb-0">{{\Illuminate\Support\Facades\App::getLocale() == 'en' ? ($consultant->has_zoom == 1 ? "The doctor has a zoom" : "The Doctor has no Zoom") : ($consultant->has_zoom == 1 ? "الطبيب لديه زوم" : "الطبيب ليس لديه زوم")}}</p>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
             </div>
             <div class="col-lg-6">
            <h4 style="margin-top:5px">{{__('User')}}</h4>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card mb-1" style="border: 1px solid #d2d2d2">
                        <div class="card-body text-center">
                            @if(!$user)
                                <img src="https://dashboard.medical001.digisolapps.com//assets/img/user_avatar.jpg" alt="avatar"
                                     class="rounded-circle img-fluid" style="width: 110px;height: 100px">
                                <h5 class="my-3">{{$user_data ? $user_data->full_name: "No Data" }}</h5>
                                <p class="text-muted mb-1">{{$user_data ? $user_data->email : "No Data" }}</p>
                            @else
                                <img src="{{ $user ? ($user->getFirstMediaFile('profile_photo') ? $user->getFirstMediaFile('profile_photo')->url : 'https://dashboard.medical001.digisolapps.com//assets/img/user_avatar.jpg' ) : 'https://dashboard.medical001.digisolapps.com//assets/img/user_avatar.jpg' }}" alt="avatar"
                                     class="rounded-circle img-fluid" style="width: 110px;height: 100px">
                                <h5 class="my-3">{{$user ? ($user->full_name ? $user->full_name : "No Data") : "No Data" }}</h5>
                            @endif
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body" style="border: 1px solid #d2d2d2;height:200px">
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">{{__('Phone')}}</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{$user ? ($user->phone_number ? $user->phone_number : "No Data") : "No Data"}}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">{{__('Country')}}</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{$user ? ($user->country ? $user->country : "No Data"):"No Data"}}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">{{__('Country Code')}}</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{$user ? ($user->country_code ? $user->country_code : "No Data") : "No Data"}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    </div>
    </div>

@endsection

@section("scripts")

    <!-- Data table plugin-->
    <script type="text/javascript" src="{{asset("assets/js/plugins/jquery.dataTables.min.js")}}"></script>
    <script type="text/javascript" src="{{asset("assets/js/plugins/dataTables.bootstrap.min.js")}}"></script>
    <script type="text/javascript">$('#sampleTable').DataTable();</script>


@endsection
