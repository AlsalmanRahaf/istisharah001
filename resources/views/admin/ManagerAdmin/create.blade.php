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
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">{{__('Create Admin')}}</h3>
                <div class="tile-body">
                    <form action="{{route('admin.adminRole.store')}}" method="POST" >
                        @csrf
                        <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Name</label>
                                <input type="text" name="full_name" class="form-control  @error('full_name') is-invalid @enderror"    placeholder="Enter full name" autocomplete="off">
                                @error('full_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">username</label>
                                <input type="text" name="username" class="form-control  @error('username') is-invalid @enderror"    placeholder="Enter username" autocomplete="off">
                                @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Email</label>
                                    <input type="email" name="email" class="form-control   @error('email') is-invalid @enderror"  placeholder="Enter email address">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{__('Password')}}</label>
                                    <input type="password" name="password" class="form-control  @error('password') is-invalid @enderror" id="exampleInputPassword1"   placeholder="Password" autocomplete="off">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                <div class="tile-footer">
                    <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>{{__('Create Admin')}}</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section("scripts")

@endsection
