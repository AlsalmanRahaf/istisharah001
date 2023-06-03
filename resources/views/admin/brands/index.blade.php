@extends("layouts.admin.app")

@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> {{__("Brands")}}</h1>
            <p>{{__("Control and view all Brands")}}</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">Brands</a></li>
        </ul>
    </div>
@endsection
@section("content")
    @include("includes.dialog")
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                            <tr>
                                <th>{{__("#ID")}}</th>
                                <th>{{__("Brand image")}}</th>
                                <th>{{__("Brand name")}}</th>
                                <th>{{__("Description")}}</th>
                                <th>{{__("category")}}</th>
                                <th>{{__("Control")}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($brands as $brand)
                                <tr>
                                    <td>{{$brand->id}}</td>
                                    <td><img src="{{$brand->getFirstMediaFile()->url}}" width="80" ></td>
                                    <td>{{$brand->name}}</td>
                                    <td>{{$brand->getDescriptionAttribute()}}</td>
                                    <td>{{$brand->category->name}}</td>
                                    <td>
                                        <a href="{{route("admin.brands.edit", $brand->id)}}" class="control-link edit"><i class="fas fa-edit"></i></a>
                                        <form action="{{route("admin.brands.destroy", $brand->id)}}" method="post" id="delete{{$brand->id}}" style="display: none" data-swal-title="{{__("Delete brand")}}" data-swal-text="{{__("Are Your Sure To Delete This Brand ?")}}" data-yes="{{__("Yes")}}" data-no="{{__("No")}}" data-success-msg="{{__("The Brand has been deleted successfully")}}">@csrf @method("delete")</form>
                                        <span href="#" class="control-link remove form-confirm" data-form-id="#delete{{$brand->id}}"><i class="far fa-trash-alt"></i></span>
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
