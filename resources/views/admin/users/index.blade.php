@extends("layouts.admin.app")
@section("page-title")
    {{__("Users")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>{{__("Users")}}</h1>
        </div>

        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="{{route("admin.users.index")}}">{{__("Users")}}</a></li>

        </ul>
    </div>
@endsection

@section("content")
    @include("includes.dialog")

    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="table-responsive" id="form_status">
                        <table class="table table-hover table-bordered text-center" id="sampleTable">
                            <thead>
                            <tr>

                                <th>{{__("Name")}}</th>
                                <th>{{__("Phone Number")}}</th>
                                <th>{{__("Type")}}</th>
                                <th>{{__("status")}}</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($users as $user)
                                <tr>

                                    <td>{{$user->full_name}}</td>
                                    <td>{{$user->phone_number}}</td>
                                    <td>
                                        <form  method="post" action="{{route("admin.users.update",["user_id" => $user->id])}}">
                                            @csrf
                                            @method("put")
                                            <select onchange="this.form.submit()" name="type" class="form-control">
                                                <option value="c" {{selected("type", "c", $user)}}>{{__("Consultant")}}</option>
                                                <option value="a" {{selected("type", "a", $user)}}>{{__("Admin")}}</option>
                                                <option value="u" {{selected("type", "u", $user)}}>{{__("user")}}</option>
                                                <option value="cph" {{selected("type", "cph", $user)}}>{{__("Consultant Pharmacist")}}</option>
                                                <option value="cn" {{selected("type", "cn", $user)}}>{{__("Consultant nutrition")}}</option>
                                                <option value="cd" {{selected("type", "cd", $user)}}>{{__("Consultant diabetes")}}</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="text-center">
                                        <form  method="post" action="{{route("admin.users.update",["user_id" => $user->id])}}">
                                            @csrf
                                            @method("put")
                                            <select onchange="this.form.submit()" name="status" class="form-control">
                                                   <option value=0 {{selected("status", 0, $user)}}>{{__("Inactive")}}</option>
                                                   <option value=1 {{selected("status", 1, $user)}}>{{__("Active")}}</option>
                                                   <option value=2 {{selected("status", 2, $user)}}>{{__("Block")}}</option>
                                            </select>
                                        </form>
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

@endsection
