@extends("layouts.admin.app")
@section("page-title")
    {{__("Dashboard")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>{{__("All Appointments")}}</h1>

        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="  fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("appointments")}}</a></li>
        </ul>
    </div>
@endsection

@section("content")
    @include("includes.dialog")
    @if(Session::has('collision'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{Session::get('collision') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if(Session::has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{Session::get('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if(Session::has('edit_success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{Session::get('edit_success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <form action="{{route('admin.date.index')}}" method="get">
                <div class="tile d-flex  align-items-center">
                    <select class="form-control m-2 w-50  @if($errors->has('object_id')) is-invalid @endif" style="margin: 5px 0px;height: 45px;background: none;padding:13px;border-radius: 7px" name="object_id" id="doctors">
                        @if(!is_null($selected_doctor))
                            <option value="{{$selected_doctor->object_id}}" selected>{{$selected_doctor->full_name}}</option>
                        @else
                            <option value="">--{{__('Select Doctor')}}--</option>
                        @endif
                        @foreach($doctors as $doctor)
                                @if(!is_null($selected_doctor))
                                    @if($selected_doctor->object_id != $doctor->object_id)
                                        <option value="{{$doctor->object_id}}">{{$doctor->full_name}}</option>
                                   @endif
                                @else
                                    <option value="{{$doctor->object_id}}">{{$doctor->full_name}}</option>
                                @endif
                        @endforeach
                    </select>
                    <button class="btn btn-danger m-2" type="submit">{{__('Filter')}}</button>
                    <button class="btn btn-dark d-flex justify-content-end" type="submit" name="reset" value="reset">
                        {{__('Reset All Appointments')}}</button>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered text-center" id="sampleTable" >
                            <thead>
                            <tr>
                                <th>{{__("#ID")}}</th>
                                <th>{{__("Booking Date")}}</th>
                                <th>{{__("User Name")}}</th>
                                <th>{{__("Doctor Name")}}</th>
                                <th>{{__("Time From")}}</th>
                                <th>{{__("Time To")}}</th>
                                <th>{{__("Status")}}</th>
                                <th>{{__("Online/Direct")}}</th>
                                <th>{{__("Cancellation")}}</th>
                                <th>{{__("Details")}}</th>
                                <th>{{__("Change Booking Time Slot")}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($users_booking)
                                @foreach($users_booking as $index => $users)
                                    <tr>
                                        <td>{{$users->reservation_record_id}}</td>
                                        <td>{{$users->date}}</td>
                                        @php
                                         $userName = \App\Models\UserBookings::where('reservation_record_id',$users->reservation_record_id)->first()
                                        @endphp
                                        <td>{{$users->user ? ($users->user->full_name ? $users->user->full_name : $userName->full_name) : $userName->full_name }}</td>
                                        <td>{{$users->full_name ? $users->full_name :  "No Doctor Name" }}</td>
                                        @php
                                            $slot_time = App\Models\TimeSlot::where('id',$users->slot_id)->first();
                                            $mytime = \Carbon\Carbon::now();
                                            $currentDate = $mytime->toDateString();
                                        @endphp
                                        @if($slot_time)
                                            <td>{{$slot_time->time_from? date('h:i',strtotime($slot_time->time_from)) : "No Data"}}</td>
                                            <td>{{$slot_time->time_to ? date('h:i',strtotime($slot_time->time_to)) : "No Data"}}</td>
                                            <td>{{\Illuminate\Support\Facades\App::getLocale() == 'en' ? ($currentDate <= $users->date? "Coming" : "Expired") : ($currentDate <= $users->date? "قادم" : "منتهي")}}</td>
                                            <td>{{\Illuminate\Support\Facades\App::getLocale() == 'en' ? ($users->is_online == 1 ? 'Online' : 'Direct') : ($users->is_online == 1 ? 'عبر الانترنت' : 'مباشر')}}</td>
                                            <td>
                                                <select id="status" class="status form-control" onchange="ajaxCall(this,{{$users->is_cancelled}})"  data-id="{{$users->reservation_record_id}}">
                                                    <option value="{{$users->is_cancelled}}" selected>{{\Illuminate\Support\Facades\App::getLocale() == 'en' ? ($users->is_cancelled == 1 ? 'Cancelled' : 'Un Cancelled') :  ($users->is_cancelled == 1 ? 'ملغي' : 'غير ملغي')}}</option>
                                                    @if($users->is_cancelled == 1)
                                                        <option value="3">{{\Illuminate\Support\Facades\App::getLocale() == 'en' ? 'Un Cancelled' : 'غير ملغي' }}</option>
                                                    @elseif($users->is_cancelled == 0)
                                                        <option value="1" >{{\Illuminate\Support\Facades\App::getLocale() == 'en' ? 'Cancelled': 'ملغي' }}</option>
                                                    @endif
                                                </select>
                                                <div class="modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
                                                    <div class="modal-dialog" role="document" >
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">{{__('Cancelled Reason')}}</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="hide()">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form>
                                                                    <div class="form-group">
                                                                        <label for="Cancelled-Reason" class="col-form-label">{{__('Cancelled Reason')}}:</label>
                                                                        <input type="text" class="form-control" id="Cancelled-Reason" style="border: 1px solid black"><br/>
                                                                        <p id="error" style="display: none;color: red">
                                                                            {{__('The Cancelled Reason Should Not Be Empty')}}</p>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="hide()" style="margin: 5px">
                                                                    {{__('Close')}}</button>
                                                                <button type="button" class="btn btn-primary" onclick="save()" style="margin: 5px">
                                                                    {{__('Save')}}</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        @endif
                                        <td>
                                            <a href="{{route('admin.date.details',[$users->user->id ?? 0,$users->object_id,$users->reservation_record_id])}}"  class="btn btn-danger">
                                                {{__("Details")}}
                                            </a>
                                        </td>
                                        <td><a class="btn btn-success" href="{{route('admin.date.edit',[$users->reservation_record_id,$users->user->id])}}"><i class="fa fa-lg fa-edit"></i></a></td></tr>
                                @endforeach
                            @endif
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
    <script>
        $("#doctors").select2({
            width: "30%",
        });
        function hide(){
            $('#exampleModal').hide();
        }
        function save(){
            let val = $('#Cancelled-Reason').val();
            const bookingId =  localStorage.getItem("appontmentId")
            const statusId =  localStorage.getItem("statusId")
            console.log(bookingId)
            let url = window.location.href;
            if(!val){
                $('#Cancelled-Reason').css("border","1px solid red");
                $('#error').css("display","block");
            }else {
                $.ajax({
                    url: url,
                    type: "GET",
                    data: {statusId: statusId, canselReason: val, bookingId: bookingId},
                    timeout: 2000,
                    success: function (result, textStatus, jqXHR) {
                        window.location.reload()
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        window.location.reload()
                    }
                });
                $('#exampleModal').hide();
            }

        }
        function ajaxCall(select,id){
            let val = $(select).val();
            let bookingId = $(select).attr('data-id');
            localStorage.setItem("appontmentId",bookingId)
            localStorage.setItem("statusId",val)
            console.log(bookingId)
            console.log(val)
            let url = window.location.href;
            console.log(url)
            if(val == 1){
                $('#exampleModal').show();
            }else if(val == 3){
                $.ajax({
                    url: url,
                    type:"GET",
                    data:{statusId:val,bookingId:bookingId},
                    timeout: 2000,
                    success: function (result, textStatus, jqXHR) {
                        window.location.reload()
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        window.location.reload()
                    }
                });
            }
        }

    </script>
@endsection
