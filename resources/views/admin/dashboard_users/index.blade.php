@extends("layouts.admin.app")
@section("page-title")
    Admins
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> Admins</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">Admins</a></li>
        </ul>
    </div>
@endsection
@section("content")
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="table-responsive" id="form_status">
                    <table class="table table-hover table-bordered" id="sampleTable">
                        <thead>
                            <tr>
                                <th>Full name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>status</th>
                                <th>Email Verified</th>
                                <th>Created at</th>
                                <th>Updated at</th>
                                <th>Control</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            @if(!$user->is_admin)
                            <tr>
                                <td>{{$user->full_name}}</td>
                                <td>{{$user->username}}</td>
                                <td>{{$user->email}}</td>

                                @if(!$user->is_super_admin)
                                <td class="text-center">
                                    <div class="form-group">
                                        <div class="toggle-flip changes-status">
                                            <label>
                                                <input  type="checkbox" class="change-status" name="status" data-url="{{ route("ajax.Admin.update")}}" data-id="{{$user->id}}" data-status="{{$user->status}}" {{ checked("status", 1, $user)}}><span class="flip-indecator" data-toggle-on="{{__("ON")}}" data-toggle-off="{{__("OFF")}}"></span>
                                            </label>
                                        </div>
                                    </div>
                                </td>
                                @else
                                    <td class="text-center">
                                        <div class="form-group">
                                            <div class="toggle-flip">
                                                <label>
                                                    <input  type="checkbox" disabled name="status"   {{ checked("status", 1, $user)}}><span class="flip-indecator" data-toggle-on="{{__("ON")}}" data-toggle-off="{{__("OFF")}}"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </td>
                                @endif



                                <td>{{!empty($user->email_verified_at) ? "Yes" : "No"}}</td>
                                <td>{{$user->created_at->diffForHumans()}}</td>
                                <td>{{$user->updated_at->diffForHumans()}}</td>
                                <td>
                                    <div class="btn-group">
                                        @if($user->is_super_admin)
                                            <a href="{{route("admin.admins.edit", $user->id)}}" class="control-link edit"><i class="fas fa-edit"></i></a>
                                            <a href="{{route("admin.admins.show", $user->id)}}" style="color:blue" class="control-link  "><i class="fa fa-eye"></i></a>
                                        @else
                                            <a href="{{route("admin.admins.edit", $user->id)}}" class="control-link edit"><i class="fas fa-edit"></i></a>
                                            <a href="{{route("admin.admins.show", $user->id)}}" style="color:blue" class="control-link "><i class="fa fa-eye"></i></a>
                                            <form action="{{route("admin.admins.destroy", $user->id)}}" method="post" id="delete{{$user->id}}" style="display: none" data-swal-title="Delete Property" data-swal-text="{{__("Are Your Sure To Delete This Admin ?")}}" data-yes ="{{__("Yes")}}" data-no="{{__("No")}}" data-success-msg="{{__("the Admin has been deleted successfully")}}">@csrf @method("delete")</form>
                                            <span href="#" class="control-link remove form-confirm" data-form-id="#delete{{$user->id}}"><i class="far fa-trash-alt"></i></span>
                                        @endif
                                    </div>
{{--                                    <form action="{{route('admin.admins.destroy',$user->id)}}" method="post" id="delete{{$user->id}}" style="display: none">@csrf @method("delete")</form>--}}
{{--                                    <span href="#" class="control-link remove" onclick='document.getElementById("delete{{$user->id}}").submit()'><i class="far fa-trash-alt"></i></span>--}}
                                </td>
                            </tr>
                            @endif
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
