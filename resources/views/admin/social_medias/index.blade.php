@extends("layouts.admin.app")
@section("page-title")
    {{__("Dashboard")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>{{__("Social Media")}}</h1>

        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("Instructions And Laws")}}</a></li>
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

                                <th>{{__("Image")}}</th>
                                <th>{{__("Url")}}</th>
                                <th>{{__("Control")}}</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($socialMedias as $socialMedia)
                                <tr>

                                    <td><img src="{{$socialMedia->getFirstMediaFile("SocialMedia")->url}}" width="175" alt=""></td>
                                    <td><a target="_blank" href="{{$socialMedia->url}}">{{$socialMedia->url}}</a></td>
                                    <td>
                                        <a href="{{route("admin.social-media.edit",$socialMedia->id)}}" class="control-link edit"><i class="fas fa-edit"></i></a>
                                        <form action="{{route("admin.social-media.destroy", ["id" => $socialMedia->id])}}" method="post" id="delete{{$socialMedia->id}}" style="display: none" data-swal-title="{{__("Delete")}}" data-swal-text="{{__("Are Your Sure To Delete This Data ?")}}" data-yes="{{__("Yes")}}" data-no="{{__("No")}}" data-success-msg="{{__('the Data has been deleted successfully')}}">@csrf @method("delete")</form>
                                        <span href="#" class="control-link remove form-confirm" data-form-id="#delete{{$socialMedia->id}}"><i class="far fa-trash-alt"></i></span>
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
