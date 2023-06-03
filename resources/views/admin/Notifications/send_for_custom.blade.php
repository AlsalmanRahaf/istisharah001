@extends("layouts.admin.app")
@section("page-title")
    {{__("send notification")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>{{__("Notification")}}</h1>
            <p>{{__("Create new notification")}}</p>
        </div>

        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("Create notification")}}</a></li>
        </ul>
    </div>
@endsection
@section("content")
@include("includes.dialog")

    <div class="row">
        <div class="col-lg-10 m-auto">
            <div class="tile">
                <h3 class="tile-title">{{__("Create notification")}}</h3>
                <div class="tile-body">
                    <form method="post" action="{{Route("admin.Notification.send")}}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" class="form-control"  value=1 name="type"  class="form-control" >

                        <div class="row"  >
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div>
                                        <label class="control-label">{{__("Title")}}</label>
                                        <input type="text" @if($errors->has('Title')) is-invalid @endif class="form-control"  name="Title"  class="form-control" >
                                        @error("Title")
                                        <div class="mt-2 input-error text-danger">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="control-label">{{__("Description")}}</label>
                                        <textarea cols="2" @if($errors->has('Description')) is-invalid @endif rows="22" name="Description"  class="form-control" ></textarea>
                                    </div>
                                </div>
                                @error("Description")
                                <div class="input-error text-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="table-responsive" id="form_status">
                                    <table class="table table-hover table-bordered text-center" id="sampleTable">
                                        <thead>
                                        <tr>
                                            <th>{{__("Select")}}</th>
                                            <th>{{__("Phone Number")}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($users as $user)
                                            <tr>
                                                <td><input type="checkbox" class="form-control"  name="id[]" value="{{$user->id}}"></td>
                                                <td>{{$user->phone_number}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    @error("id")
                                    <div class="input-error text-danger">must select one at least</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="tile-footer">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> {{__("Send")}}</button>
                        </div>
                    </form>
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


