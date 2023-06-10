@extends("layouts.admin.app")
@section("page-title")
    Dashboard
@endSection
@section("page-nav-title")

    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> Dashboard</h1>
            <p>profile Dashboard</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">index</a></li>
        </ul>
    </div>
@endsection

@section("content")
        <div class="row user">

            <div class="col-md-12">
                <div class="tab-content">
                    <div class="tab-pane active" id="user-timeline">
                        <div class="timeline-post">
                            <a href="{{ route("admin.admins.profile") }}" style="float: right;" class="btn btn-rounded btn-success mb-5"> Edit Profile</a>
                            <div class="post-media"><a href="#"><img src="{{asset('assets/img/user_avatar.jpg')}}" style="height: 70px; width: 100px; border-radius: 50%; "></a>
                                <div class="content">
                                    <h5><a href="#">{{$full_name}}</a></h5>
                                    <p class="text-muted"><small>{{$email}}</small></p>
                                </div>
                            </div>
                            <div class="post-content">
                                <p> </p>
                            </div>
                            <ul class="post-utility">
                                <li class="likes"><a href="#"><i class="fa fa-fw fa-lg fa-thumbs-o-up"></i>{{$full_name}}</a></li>
                                <li class="shares"> <a href="#"><i class="fa fa-envelope" aria-hidden="true"></i> {{$email}} </a></li>
                                <li class="comments"><i class="fa fa-fw fa-lg fa-comment-o"></i>{{$username}}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="user-settings">
                        <div class="tile user-settings">
                            <h4 class="line-head">Settings</h4>
                            <form action="{{route('admin.profile.update')}}" method="POST" >
                                @csrf
                                <input type="hidden" name="id" value="{{ $id }}">
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label>Full Name</label>
                                        <input class="form-control" @if($errors->has('full_name')) is-invalid @endif" type="text" name="full_name" placeholder="{{__("Enter Full Name")}}" value="{{inputValue("full_name", $full_name)}}">
                                        @error("full_name")
                                        <div class="input-error">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label>Username</label>
                                        <input class="form-control"  @if($errors->has('username')) is-invalid @endif" type="text" name="username" placeholder="{{__("Enter Username")}}" value="{{inputValue("username", $username)}}">
                                        @error("username")
                                        <div class="input-error">{{$message}}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 mb-4">
                                        <label>Email</label>
                                        <input class="form-control"  @if($errors->has('email')) is-invalid @endif" type="text" name="email" placeholder="{{__("Enter Email")}}" value="{{$email}}">
                                        @error("email")
                                        <div class="input-error">{{$message}}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-10">
                                    <div class="col-md-12">
                                        <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="user-Password">
                        <div class="tile user-settings">
                            <h4 class="line-head">Chanage Password</h4>
                            <form action="{{route('admin.profile.ChanagePassword')}}" method="POST" >
                                @csrf
                                <input type="hidden" name="id" value="{{ $id }}">
                                <div class="row">
                                    <div class="col-md-8 mb-4">
                                        <label>Password</label>
                                        <input class="form-control"  @if($errors->has('oldpassword')) is-invalid @endif" type="text" name="oldpassword" placeholder="{{__("Enter Current password")}}">
                                        @error("oldpassword")
                                        <div class="input-error">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-8 mb-4">
                                        <label>New Password</label>
                                        <input class="form-control"  @if($errors->has('password')) is-invalid @endif" type="text" name="password" placeholder="{{__("Enter New password")}}">
                                        @error("password")
                                        <div class="input-error">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-8 mb-4">
                                        <label>Confirm Password</label>
                                        <input class="form-control"  @if($errors->has('confirmed_password')) is-invalid @endif" type="text" name="password" placeholder="{{__("Enter Confirmed password")}}">
                                        @error("confirmed_password")
                                        <div class="input-error">{{$message}}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-10">
                                    <div class="col-md-12">
                                        <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

@endsection

@section("scripts")

    <script>

    </script>
@endsection
