@extends("layouts.admin.app")

@section("page-title")
    Dashboard
@endSection

@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> Roles & Permissions</h1>
            <p>create new role</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="{{route("admin.roles.index")}}">Roles & Permissions</a></li>
            <li class="breadcrumb-item"><a href="#">Create</a></li>
        </ul>
    </div>
@endsection
@section("content")
    <div class="row">
        <div class="col-lg-10 m-auto">
            <div class="tile">
                <h3 class="tile-title">Create New Role</h3>
                <div class="tile-body">
                    <form method="post" action="{{route("admin.roles.store.permissions")}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">Role name</label>
                                    <input class="form-control @if($errors->has('name')) is-invalid @endif" type="text" name="name" placeholder="Enter Role name">
                                </div>
                                @error("name")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="tile-footer">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Create</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
@section("scripts")

@endsection
