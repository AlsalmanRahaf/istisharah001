@extends("layouts.admin.app")
@section("page-title")
    Dashboard
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> Dashboard</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("App Setting")}}</a></li>
        </ul>
    </div>
@endsection

@section("content")
        <div class="row">
            <div class="col">
                <div class="jumbotron">
                    <h4 class="display-5">{{__("App Setting")}} </h4>
                    @if(!$AppSetting->isEmpty())
{{--                        // $data is not empty--}}
                    @else
                        <a class="btn btn-success" style="float: right;" href=""> {{__('Create promo code')}}</a>
                    @endif
                </div>
            </div>
        </div>
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="sampleTable">
                                <thead>
                                <tr>
                                    <th>{{__("ID")}}</th>
                                    <th>{{__("Terms_And_Conditions")}}</th>
                                    <th>{{__("Promo code activation")}}</th>
                                    <th>{{__("created_at")}}</th>
                                    <th>{{__("updated_at")}} </th>
                                    <th>{{__("Action")}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($AppSetting as $Setting)
                                <tr>
                                    <td>{{$Setting->id}}</td>
                                    <td>{{$Setting->Terms_And_Conditions}}</td>
                                    <td>
                                        @if ($Setting->contest)
                                            <span class="badge badge-success">{{__('is_activia')}}</span>
                                        @else
                                            <span class="badge badge-danger">{{__('Not_activia')}}</span>
                                        @endif
                                    </td>
                                    <td>{{$Setting->created_at->diffForHumans()}}</td>
                                    <td>{{$Setting->updated_at->diffForHumans() }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a class="btn btn-success" href="{{route('admin.App-Setting.edit')}}"><i class="fa fa-lg fa-edit"></i></a>
{{--                                            <a class="btn btn-danger" href="{{route('admin.App-Setting.destroy',$Setting->id)}}"><i class="fa fa-lg fa-trash"></i></a>--}}
                                        </div>
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
    <script type="text/javascript" src="{{asset('assets/js/plugins/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/plugins/dataTables.bootstrap.min.js')}}"></script>
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
