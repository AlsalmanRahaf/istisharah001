@extends("layouts.admin.app")
@section("page-title")
    {{__("Dashboard")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1>{{__("Other Consultant Messages")}}</h1>
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
                                <th>{{__("chat member")}}</th>
                                <th>{{__("Show Chat")}}</th>
                                {{--                                <th>{{__("status")}}</th>--}}
                                <th>{{__("type")}}</th>
                                <th>{{__("standard")}}</th>
                                <th>{{__("consultations status")}}</th>
                                <th>{{__("created_at")}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($rooms as $room)
                                <tr>

                                    <td>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#member-role-{{$room->id}}">
                                            {{__("View")}}
                                        </button>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#chat-role-{{$room->id}}">
                                            {{__("chat")}}
                                        </button>
                                    </td>
                                    <td>@if($room->type == 1){{__("One To One")}}@else{{__("Group")}}@endif</td>
                                    <td>@if($room->standard == 1){{__("Normal")}}@else{{__("Vip")}}@endif</td>
                                    <td>
                                        @switch($room->UserCustomConsultation->consultation_status)
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
                                            @case(6)
                                            <span class="status-box bg-vip-color">{{__("Vip")}}</span>
                                            @break
                                        @endswitch
                                    </td>
                                    <td>{{$room->created_at}}</td>

                                    {{-- modal for member--}}
                                    <div class="modal fade" id="member-role-{{$room->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lgx">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="member-role-{{$room->id}}">{{__("Chat member")}}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    @foreach($room->room_members as $member)
                                                        <span class="field-view">{{$member->users->full_name != null ? $member->users->full_name:$member->users->phone_number}}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- modal for member--}}

                                    {{-- modal for chat--}}
                                    <div class="modal fade bd-example-modal-lg" id="chat-role-{{$room->id}}" tabindex="-1"  aria-labelledby="myLargeModalLabel"  aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="chat-role-{{$room->id}}">{{__("Chat member")}}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
                                                    <div class="container">
                                                        <div class="row clearfix">
                                                            <div class="col-lg-12">
                                                                <div class="card chat-app">
                                                                    <div class="chat">
                                                                        <div class="chat-history over-flow-chat" >
                                                                            <ul class="m-b-0">
                                                                                @if($room->chat !=null)
                                                                                    @foreach($room->chat as $ch)
                                                                                        <li class="clearfix">
                                                                                            <div class="message-data @if($ch["direction"] != null && $ch["direction"] == "sender") text-right @endif">
                                                                                                <h4 class="message-data-time">{{$ch["user_name"]}}<img  src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="avatar"></h4>
                                                                                                <span class=" message-data-time">{{$ch["created_at"]}}</span>
                                                                                            </div>
                                                                                            <div class="message other-message  @if($ch["direction"] != null && $ch["direction"] == "sender") float-right @endif">
                                                                                                @if($ch["type"] == 1)
                                                                                                    {{$ch["text"]?? null}}
                                                                                                @elseif($ch["type"]==2)
                                                                                                    @if(count($ch["media"]) > 0)
                                                                                                        @switch($ch["media"][0][1])
                                                                                                            @case(2)
                                                                                                            <div class="zoom-within-container">
                                                                                                                <img  class="img-style" src="{{$ch["media"][0][0]}}" width="200" height="100%" alt="avatar">
                                                                                                            </div>

                                                                                                            @break
                                                                                                            @case(3)
                                                                                                            <video width="320" height="240" controls>
                                                                                                                <source src="{{$ch["media"][0][0]}}" type="video/mp4">
                                                                                                                Your browser does not support the video tag.
                                                                                                            </video>
                                                                                                            @break
                                                                                                            @case(4)
                                                                                                            <audio controls>
                                                                                                                <source src="{{$ch["media"][0][0]}}" type="audio/mpeg">
                                                                                                                Your browser does not support the audio element.
                                                                                                            </audio>
                                                                                                            @break
                                                                                                            @case(5)
                                                                                                            <div>
                                                                                                                <object data="{{$ch["media"][0][0]}}" type="application/pdf" width="300" height="200">
                                                                                                                    alt : <a href="{{$ch["media"][0][0]}}">Error.pdf</a>
                                                                                                                </object>
                                                                                                            </div>
                                                                                                            @break
                                                                                                        @endswitch
                                                                                                    @endif
                                                                                                @endif
                                                                                            </div>
                                                                                        </li>
                                                                                    @endforeach
                                                                                @endif
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- end modal for caht--}}
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
    <script type="text/javascript">$('#sampleTable').DataTable({ "bSort" : false } );</script>


@endsection
