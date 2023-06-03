@extends("layouts.admin.app")

@section("page-title")
    Dashboard
@endSection

@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> Roles & Permissions</h1>
            <p>Control and view all Roles & Permissions</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">Roles & Permissions</a></li>
        </ul>
    </div>
@endsection
@section("content")
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                            <tr>
                                <th>Role Name</th>
                                <th>Permissions</th>
                                <th>Control</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($roles as $role)
                                <tr>
                                    <td>{{$role->name}}</td>
                                    <td>
                                    <!-- Permission View Button -->
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#permission-role-{{$role->id}}">
                                            View
                                        </button>
                                    </td>
                                    <!-- Permission Box-->
                                    <div class="modal fade" id="permission-role-{{$role->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lgx">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Permissions</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                        @foreach($role->permissions as $permission)
                                                            <span class="field-view">{{$permission->name}}</span>
                                                        @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <td>
                                        <a href="{{route("admin.roles.edit", $role->id)}}" class="control-link edit"><i class="fas fa-edit"></i></a>
                                        <form action="{{route("admin.roles.destroy", $role->id)}}" method="post" id="delete{{$role->id}}" style="display: none" data-swal-title="Delete Role" data-swal-text="Are Your Sure To Delete This Role ?" data-yes="Yes" data-no="No" data-success-msg="the role has been deleted succssfully">@csrf @method("delete")</form>
                                        <span href="#" class="control-link remove form-confirm" data-form-id="#delete{{$role->id}}"><i class="far fa-trash-alt"></i></span>
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
    <script type="text/javascript">

        $(".change-type-user input[type=\"checkbox\"]").on("click",function (){
            let val = $(this).data("val");
            let userId = $(this).data("userid");
            let newType = val == 'user' ? 'vendor' : 'user';
            let checkBox = $(this);
            $.ajax({
                type: "POST",
                url:"/api/users/app/change_type",
                data:{userType: newType, userId: userId}, // serializes the form's elements.
                success: function(response)
                {
                    if(response.status == 1){
                        if(newType == 'user')
                            checkBox.attr('checked', true);
                        else
                            checkBox.attr('checked', false);
                        checkBox.attr("data-val",newType);
                    }
                },
                error:function(){
                    console.error("you have error");
                }
            });
        });
    </script>
@endsection
