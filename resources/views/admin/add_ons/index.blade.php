@extends("layouts.admin.app")

@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> {{__("Add Ons")}}</h1>
            <p>{{__("Control and view all Add Ons")}}</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Items")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{$item->name}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("Add Ons")}}</a></li>
        </ul>
    </div>
@endsection
@section("content")
    @include("includes.dialog")
    <div class="row">
        <div class="col-lg-12">
            <div class="tile">
                <div class="row">
                    <div class="col-lg-3">
                        <img src="{{$item->getFirstMediaFile("main_photo")->url}}" alt="" class="full-box-img">
                    </div>
                    <div class="col-lg-9">
                        <h3 class="tile-title">{{$item->name}} </h3>
                        <h3 class="tile-title">{{$item->price}}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-title-w-btn"><a href="{{route('admin.items.add_ons.create', $item->id)}}" class="btn btn-primary">{{__("Create New Add On's")}}</a></div>
                <div class="tile-title">{{__("Add Ons")}}</div>
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                            <tr>
                                <th>{{__("#ID")}}</th>
                                <th>{{__("Name")}}</th>
                                <th>{{__("Type Input")}}</th>
                                <th>{{__("Control")}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($addOns as $addOn)
                                <tr>
                                    <td>{{$addOn->id}}</td>
                                    <td>{{$addOn->name}}</td>
                                    <td>{{$addOn->type_input == 1 ? "Single" : "Multi"}}</td>
                                    <td>
                                        <a href="{{route("admin.items.add_ons.edit", ["item_id" => $item->id, "id" => $addOn->id])}}" class="control-link edit"><i class="fas fa-edit"></i></a>
                                        <form action="{{route("admin.items.add_ons.destroy", ["item_id" => $item->id, "id" => $addOn->id])}}" method="post" id="delete{{$addOn->id}}" style="display: none" data-swal-title="{{__("Delete Add On")}}" data-swal-text="{{__("Are Your Sure To Delete This Add On ?")}}" data-yes="{{__("Yes")}}" data-no="{{__("No")}}" data-success-msg="{{__("the Add On has been deleted successfully")}}">@csrf @method("delete")</form>
                                        <span href="#" class="control-link remove form-confirm"  data-form-id="#delete{{$addOn->id}}"><i class="far fa-trash-alt"></i></span>
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
