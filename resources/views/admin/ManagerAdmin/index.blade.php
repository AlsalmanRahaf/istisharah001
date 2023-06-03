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
            <li class="breadcrumb-item"><a href="#">{{__("Admins")}}</a></li>
            <li class="breadcrumb-item"><a href="#">index</a></li>
        </ul>
    </div>
@endsection

@section("content")
        <div class="row">
            <div class="col">
                <div class="jumbotron">
                    <h4 class="display-5">{{__('All Admins')}} </h4>
                    <a class="btn btn-success" style="float: right;" href="{{ route('admin.adminRole.create')}}"> {{__('Create Admin')}}</a>
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
                                    <th>{{__("Full Name")}}</th>
                                    <th>{{__("User Name")}}</th>
                                    <th>{{__("Email")}}</th>
                                    <th>{{__("is_super_admin")}} </th>
                                    <th>{{__("Action")}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($Admins as $Admin)
                                <tr>
                                    <td>{{$Admin->full_name}}</td>
                                    <td>{{$Admin->username }}</td>
                                    <td>{{$Admin->email}}</td>
                                    <td>
                                        @if ($Admin->is_super_admin)
                                            <span class="badge badge-success">{{__('is_super')}}</span>
                                        @else
                                            <span class="badge badge-danger">{{__('Not_super')}}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a class="btn btn-primary" href="{{ route('admin.adminRole.show',$Admin->id)}}"><i class="fa fa-eye"></i></a>
                                            <a class="btn btn-success" href="{{route('admin.adminRole.edit',$Admin->id)}}"><i class="fa fa-lg fa-edit"></i></a>
                                            <a class="btn btn-danger" href="{{route('admin.adminRole.destroy',$Admin->id)}}"><i class="fa fa-lg fa-trash"></i></a>
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
