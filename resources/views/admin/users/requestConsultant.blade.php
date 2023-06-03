@extends("layouts.admin.app")
@section("page-title")
    {{__("Request consultant")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <p>{{__("request  Users")}}</p>
        </div>

        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="{{route("admin.users.index")}}">{{__("Users")}}</a></li>

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
                                <th>{{__("ID")}}</th>
                                <th>{{__("Phone Number")}}</th>
                                <th>{{__("Attachment")}}</th>
                                <th>{{__("show chat")}}</th>
                                <th>{{__("status")}}</th>
                                <th>{{__("Action")}}</th>
                                <th>{{__("Control")}}</th>
                            </tr>
                            </thead>
                            <tbody>
{{--                            {{dd($RequestsConsultants)}}--}}
                            @foreach($RequestsConsultants as $Reqest)
                                <tr>
                                    <td>{{$Reqest->id}}</td>
                                    <td>{{$Reqest->users->phone_number}}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#request-{{$Reqest->id}}">
                                            {{__("show")}}
                                        </button>
                                        <div class="modal fade" id="request-{{$Reqest->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="request-{{$Reqest->id}}">{{__("attach")}}</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @if($Reqest->users->getFirstMediaFile("attach_photo") != null)


                                                                <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                                                                    <div class="carousel-inner">
                                                                        <div class="carousel-item active">
                                                                            <img src="{{$Reqest->users->getFirstMediaFile("attach_photo")[0]->url}}" class="d-block w-100" alt="...">
                                                                        </div>
                                                                        @foreach($Reqest->users->getFirstMediaFile("attach_photo") as $image)
                                                                                    <div class="carousel-item">
                                                                                        <img src="{{$image->url}}"  class="d-block w-100" alt="...">
                                                                                    </div>
                                                                       @endforeach

                                                                    </div>
                                                                    <button class="carousel-control-prev"  type="button" data-target="#carouselExampleControls" data-slide="prev">
                                                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                                        <span class="sr-only">Previous</span>
                                                                    </button>
                                                                    <button class="carousel-control-next"   type="button" data-target="#carouselExampleControls" data-slide="next">
                                                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                                        <span class="sr-only">Next</span>
                                                                    </button>
                                                                </div>

                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </td>
                                    @foreach($rooms as $room)
                                        @if($Reqest->requestChat !=null)
                                            @if($Reqest->requestChat->room_id == $room->id)
                                            <td>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#chat-role-{{$room->id}}">
                                                    {{__("chat")}}
                                                </button>
                                            </td>
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
                                        @endif
                                        @endif
                                    @endforeach
                                    <td>
                                        @switch($Reqest->status)
                                            @case(1)
                                            <span class="status-box  bg-warning-color">{{__("Waiting")}}</span>
                                            @break
                                            @case(2)
                                            @case(4)
                                            <span class="status-box  bg-active-color">{{__("Accepted")}}</span>
                                            @break
                                            @case(3)
                                            <span class="status-box  bg-non-active-color">{{__("Non Accepted")}}</span>
                                            @break
                                        @endswitch
                                    </td>
                                    <td class="text-center">
                                        <form method="post" action="{{route("admin.users.UpdateRequestConsultant",["id" => $Reqest->id])}}">
                                            @csrf
                                            @method("put")
                                         <select  onchange="this.form.submit()" name="status"  class="form-control">
                                           @switch($Reqest->status)
                                                @case(1)
                                                  <option>{{__("Waiting")}}</option>
                                                  <option value=2>{{__("Accepted Medical")}}</option>
                                                  <option value=4>{{__("Accepted Other")}}</option>
                                                 <option value=3>{{__("Non-Accepted")}} </option>
                                                @break
                                                @case(2)
                                                    <option value=2>{{__("Accepted Medical")}}</option>
                                                 @break
                                                 @case(3)
                                                     <option value=3>{{__("Non-Accepted")}}</option>
                                                 @break
                                                 @case(4)
                                                 <option value=4>{{__("Accepted Other")}}</option>
                                                 @break
                                           @endswitch
                                         </select>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="{{route("admin.users.DeleteRequestConsultant", ["id" => $Reqest->id])}}" method="post" id="delete{{$Reqest->id}}" style="display: none" data-swal-title="{{__("Delete Request Consultant")}}" data-swal-text="{{__("Are You Sure To Delete This Request Consultant?")}}" data-yes="{{__("Yes")}}" data-no="{{__("No")}}" data-success-msg="{{__("the Request Consultant has been deleted succssfully")}}">@csrf @method("delete")</form>
                                        <span href="#" class="control-link remove form-confirm" data-form-id="#delete{{$Reqest->id}}"><i class="far fa-trash-alt"></i></span>
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
