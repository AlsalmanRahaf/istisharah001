@extends("layouts.admin.app")

@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> {{__("Categories")}}</h1>
            <p>{{__("Control and view all Categories")}}</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Categories")}}</a></li>
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
                                <th>#{{__("ID")}}</th>
                                <th>{{__("Category Image")}}</th>
                                <th>{{__("Category name")}}</th>
                                <th>{{__("Status")}}</th>
                                <th>{{__("Control")}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($categories as $category)
                                <tr>
                                    <td>{{$category->id}}</td>
                                    <td><img src="{{$category->getFirstMediaFile()->url}}" width="80"></td>
                                    <td>{{$category->name}}</td>
                                    <td class="text-center">
                                        <span class="status-box @if($category->status) bg-active-color @else bg-non-active-color @endif">
                                            {{$category->status ?__("Active") : __("Non-Active")}}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{route("admin.categories.edit", $category->id)}}" class="control-link edit"><i class="fas fa-edit"></i></a>
                                        <form action="{{route("admin.categories.destroy", $category->id)}}" method="post" id="delete{{$category->id}}" style="display: none" data-swal-title="{{__("Delete Category")}}" data-swal-text="{{__("Are Your Sure To Delete This Category ?")}}" data-yes="{{__("Yes")}}" data-no="{{__("No")}}" data-success-msg="{{__("the category has been deleted successfully")}}">@csrf @method("delete")</form>
                                        <span href="#" class="control-link remove form-confirm" data-form-id="#delete{{$category->id}}"><i class="far fa-trash-alt"></i></span>
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
