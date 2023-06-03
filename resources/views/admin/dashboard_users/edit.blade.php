@extends("layouts.admin.app")
@section("page-title")
    Create Admin
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> Admins</h1>
            <p>Edit new admin</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">Admin</a></li>
            <li class="breadcrumb-item"><a href="#">Edit</a></li>
        </ul>
    </div>
@endsection
@section("content")
    <div class="row">
        <div class="col-lg-10 m-auto">
            <div class="tile">
                <h3 class="tile-title">Edit New Admin</h3>
                <div class="tile-body">
                    <form method="post" action="{{route("admin.admins.update", $admin->id)}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">Full name</label>
                                    <input class="form-control @if($errors->has('full_name')) is-invalid @endif" type="text" name="full_name" placeholder="Enter Full name" value="{{$admin->full_name}}">
                                </div>
                                @error("full_name")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">Username</label>
                                    <input class="form-control @if($errors->has('username')) is-invalid @endif" type="text" name="username" placeholder="Enter Username" value="{{$admin->username}}">
                                </div>
                                @error("username")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">Email</label>
                                    <input class="form-control @if($errors->has('email')) is-invalid @endif" type="text" name="email" placeholder="example : example@example.com" value="{{$admin->email}}">
                                </div>
                                @error("email")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">Profile Photo</label>
                                    <div>
                                        <button class="btn btn-primary form-control button-upload-file" >
                                            <input class="input-file" type="file" name="profile_photo">
                                            <span class="upload-file-content">
                                            <i class="fas fa-upload fa-lg upload-file-content-icon left"></i>
                                            <span class="upload-file-content-text">Upload Photo</span>
                                        </span>
                                        </button>
                                    </div>
                                </div>
                                @error("profile_photo")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">Password</label>
                                    <input class="form-control @if($errors->has('password')) is-invalid @endif" type="password" name="password" placeholder="****">
                                </div>

                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">Confirm Password</label>
                                    <input class="form-control @if($errors->has('password')) is-invalid @endif" type="password" name="password_confirmation" placeholder="****" >
                                </div>
                            </div>
                            <br>
                            @error("password")
                            <div class="input-error">{{$message}}</div>
                            @enderror

                        </div>
                        @if(!$admin->is_super_admin)
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="exampleSelect1" >Roles</label>
                                    <select class="form-control @if($errors->has('role')) is-invalid @endif" id="NavigateList" name="role_id">
                                        <option value="">None</option>
                                        @foreach($roles as $role)
                                            <option value="{{$role->id}}" @if(old("role")) {{selected("role", $role->id)}} @endif >{{$role->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error("role")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        <div class="tile-footer">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Edit</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
@section("scripts")
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
