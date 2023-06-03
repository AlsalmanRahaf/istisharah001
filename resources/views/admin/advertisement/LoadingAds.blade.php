@extends("layouts.admin.app")
@section("page-title")
    {{__("advertisement")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <p>{{__("Loading Screen advertisement")}}</p>
        </div>
        <li class="btn btn-primary" ><a style="color: white" href="{{route("admin.Ads.create-loading-ads")}}">{{__("Create Loading advertisement")}}</a></li>
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
                                <th>{{__("ID")}}</th>
                                <th>{{__("Content")}}</th>
                                <th>{{__("Status")}}</th>
                                <th>{{__("Control")}}</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($ads as $advertisement)
                                <tr>
                                    <td>{{$advertisement->id}}</td>
                                    <td>
                                          <img src="{{$advertisement->getFirstMediaFile('LoadingAds')->url}}" width="100px" height="100px" />
                                    </td>
                                    <td class="text-center">
                                        <div class="form-group">
                                            <div class="toggle-flip changes-status">
                                                <label>
                                                    <input  type="checkbox" class="change-status" name="status" data-url="{{ route("ajax.Ads.updateloadingAds")}}" data-id="{{$advertisement->id}}" data-status="{{$advertisement->Status}}" {{ checked("Status", 1, $advertisement)}}><span class="flip-indecator" data-toggle-on="{{__("ON")}}" data-toggle-off="{{__("OFF")}}"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </td>




                                    <td>
                                        <a href="{{route("admin.Ads.Edit-loading-ads",$advertisement->id)}}" class="control-link edit"><i class="fas fa-edit"></i></a>
                                        <form action="{{route("admin.Ads.loading-ads-destroy", ["id"=>$advertisement->id])}}" method="post" id="delete{{$advertisement->id}}" style="display: none" data-swal-title="Delete Advertisement" data-swal-text="{{__("Are Your Sure To Delete This Advertisement?")}}" data-yes ="{{__("Yes")}}" data-no="{{__("No")}}" data-success-msg="{{__("the Ads has been deleted successfully")}}">@csrf @method("delete")</form>
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
