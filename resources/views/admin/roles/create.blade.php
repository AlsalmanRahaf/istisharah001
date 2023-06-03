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
                    <form method="post" action="{{route("admin.roles.store")}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">Role name</label>
                                    <input class="form-control @if($errors->has('name')) is-invalid @endif" type="text" name="name" placeholder="Enter Role name" value="{{inputValue("name")}}">
                                </div>
                                @error("name")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            @foreach($permissions as $permission)
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <div class="checkbox-correct size-1 check-box mr-2" style="display: inline-block;" >
                                        <input  type="checkbox" name="permissions[]" value="{{$permission->id}}" @if(isset($permissionsSelected[$permission->id])) checked @endif>
                                    </div>
                                    <label class="control-label">{{ $permission->name }}</label>
                                </div>
                            </div>
                            @endforeach
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
