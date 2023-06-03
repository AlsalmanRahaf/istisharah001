@extends("layouts.admin.app")

@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> Sliders Market</h1>
            <p>Control and view Sliders Market</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">Sliders Market</a></li>
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
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Slider Image</th>
                                <th>Navigate Type</th>
                                <th>Navigate Name</th>
                                <th>Status</th>
                                <th>Created at</th>
                                <th>Updated at</th>
                                <th>Control</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($sliders as $slider)
                                <tr>
                                    <td>{{$slider->id}}</td>
                                    <td><img src="{{$slider->getFirstMediaFile("slider_market_photo")->url}}" alt="" style="width: 150px"></td>
                                    <td>{{$slider->type == 1 ? "category" : "item"}}</td>
                                    <td>{{$slider->navigator_name_en}} ({{$slider->navigator_name_ar}})</td>
                                    <td>{{$slider->Status == 1 ? "Active" : "Non-Active"}}</td>
                                    <td>{{$slider->created_at}}</td>
                                    <td>{{$slider->updated_at}}</td>
                                    <td>
                                        <a href="{{route("admin.slider_market.edit", $slider->id)}}" class="control-link edit"><i class="fas fa-edit"></i></a>
                                        <form action="{{route("admin.slider_market.destroy", $slider->id)}}" method="post" id="delete{{$slider->id}}" style="display: none" data-swal-title="Delete Slider" data-swal-text="Are Your Sure To Delete This Slider ?" data-yes="Yes" data-no="No" data-success-msg="the slider has been deleted successfully">@csrf @method("delete")</form>
                                        <span href="#" class="control-link remove form-confirm" data-form-id="#delete{{$slider->id}}" ><i class="far fa-trash-alt"></i></span>
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
    <!-- Google analytics script-->
    <script type="text/javascript">
        if(document.location.hostname == 'pratikborsadiya.in') {
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
            ga('create', 'UA-72504830-1', 'auto');
            ga('send', 'pageview');
        }
    </script>
@endsection
