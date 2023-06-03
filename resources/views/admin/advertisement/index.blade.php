@extends("layouts.admin.app")
@section("page-title")
    {{__("advertisement")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <p>{{__("All advertisement")}}</p>
        </div>

        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("advertisement")}}</a></li>
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
                                <th>{{__("Content")}}</th>
                                <th>{{__("logo")}}</th>
                                <th>{{__("Type")}}</th>
                                <th>{{__("Status")}}</th>
                                <th>{{__("Control")}}</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($ads as $advertisement)
                                <tr>
                                    <td>
                                        @if($advertisement->type == 1)
                                            <div class="form-group">
                                                <div>
                                                    <textarea cols="2" rows="4" name="Text"  class="form-control" disabled>{{$advertisement->Ads_text->Data}}</textarea>
                                                </div>
                                            </div>
                                        @elseif($advertisement->type == 2)
                                            @foreach($advertisement->getFirstMediaFile('Ads') as $image)
                                                <img src="{{$image->url}}" width="100px" height="100px" />
                                            @endforeach
                                        @elseif($advertisement->type == 3)
                                            @foreach($advertisement->getFirstMediaFile('Ads') as $video)
                                                <video width="120px" height="120px" controls>
                                                    <source src="{{$video->url}}" >
                                                </video>
                                            @endforeach
                                        @elseif($advertisement->type == 4)
                                            @foreach($advertisement->getFirstMediaFile('Ads') as $audio)
                                                <audio controls>
                                                    <source src="{{$audio->url}}" type="audio/mpeg">
                                                    Your browser does not support the audio element.
                                                </audio>
                                            @endforeach
                                        @endif()
                                    </td>
                                    <td>
                                        @if($advertisement->getFirstMediaFile('Logo') !== null )
                                        <img src="{{$advertisement->getFirstMediaFile('Logo')->url}}" width="100px" height="100px" />
                                        @endif
                                    </td>
                                    <td>
                                        @if($advertisement->type == 1)
                                            {{'Text'}}
                                        @elseif($advertisement->type == 2)
                                            {{'Image'}}
                                        @elseif($advertisement->type == 3)
                                            {{'Video'}}
                                        @elseif($advertisement->type == 4)
                                            {{'Audio'}}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="form-group">
                                            <div class="toggle-flip changes-status">
                                                <label>
                                                    <input  type="checkbox" class="change-status" name="status" data-url="{{ route("ajax.Ads.update")}}" data-id="{{$advertisement->id}}" data-status="{{$advertisement->status}}" {{ checked("status", 1, $advertisement)}}><span class="flip-indecator" data-toggle-on="{{__("ON")}}" data-toggle-off="{{__("OFF")}}"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </td>



                                    <td>
                                        <a href="{{route("admin.Ads.edit", $advertisement->id)}}" class="control-link edit"><i class="fas fa-edit"></i></a>
                                        <form action="{{route("admin.Ads.destroy", ["id"=>$advertisement->id])}}" method="post" id="delete{{$advertisement->id}}" style="display: none" data-swal-title="Delete Property" data-swal-text="{{__("Are Your Sure To Delete This Property ?")}}" data-yes ="{{__("Yes")}}" data-no="{{__("No")}}" data-success-msg="{{__("the Slider has been deleted successfully")}}">@csrf @method("delete")</form>
                                        <span href="#" class="control-link remove form-confirm" data-form-id="#delete{{$advertisement->id}}"><i class="far fa-trash-alt"></i></span>
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
