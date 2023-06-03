@extends("layouts.admin.app")

@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> {{__("Items")}}</h1>
            <p>{{__("Control and view all Items")}}</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Items")}}</a></li>
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
                                <th>{{__("Item Image")}}</th>
                                <th>{{__("Item name")}}</th>
                                <th>{{__("Price")}}</th>
                                <th>{{__("Status")}}</th>
                                <th>{{__("Branches")}}</th>
                                <th>{{__("Item Data")}}</th>
                                <th>{{__("Add On's")}}</th>
                                <th>{{__("Control")}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <td>{{$item->id}}</td>
                                    <td><img src="{{$item->getFirstMediaFile("main_photo")->url}}" width="80" ></td>
                                    <td>{{$item->name}}</td>
                                    <td>{{$item->price}}</td>
                                    <td class="text-center">
                                        <span class="status-box @if($item->status == 1) bg-active-color @else bg-non-active-color @endif">
                                            {{$item->status == 1 ?__("Active") : __("Non-Active")}}
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#item-branch-{{$item->id}}">
                                            {{__("View")}}
                                        </button>

                                        <div class="modal fade" id="item-branch-{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lgx">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">{{__("Branches")}}</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @foreach($item->branches as $branch)
                                                            <span class="field-view">{{$branch->store_name}}</span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>


                                    <td>

                                        <a href="#" class="control-link" data-toggle="modal" data-target="#showDataItem{{$item->id}}">
                                            <i class="far fa-eye"></i>
                                        </a>

                                        <div class="modal fade" id="showDataItem{{$item->id}}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">

                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticBackdropLabel">{{$item->name}}</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="table-responsive">
                                                                    <table class="table table-hover table-bordered" id="sampleTable">
                                                                        <thead>
                                                                            <th>{{__("Title")}}</th>
                                                                            <th></th>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>{{__("image")}}</td>
                                                                                <td>
                                                                                    @foreach($item->getFirstMediaFile("more_photo") as $file)
                                                                                        <img src="{{$file->url}}" style="width:100px" />
                                                                                    @endforeach
                                                                                </td>
                                                                            </tr>

                                                                            <tr>
                                                                                <td>{{__("Sizes")}}</td>
                                                                                <td>
                                                                                    @foreach($item->sizes as $index => $size)
                                                                                        @if($index != 0)
                                                                                            -
                                                                                        @endif
                                                                                        {{$size->size}}
                                                                                    @endforeach
                                                                                </td>
                                                                            </tr>

                                                                            <tr>
                                                                                <td>
                                                                                    {{__("Colors")}}
                                                                                </td>
                                                                                <td>
                                                                                    @foreach($item->colors as $color)
                                                                                        <span style="background-color: {{ $color->color }};padding: 7px 16px;border-radius: 50%;margin-right: 9px;"></span>
                                                                                    @endforeach
                                                                                </td>
                                                                            </tr>

                                                                        </tbody>
                                                                    </table>
                                                                </div>

                                                            </div>
                                                            <div class="card-body">
                                                                <hr>
                                                                <h5 class="card-title">{{__("Category Name")}}</h5>
                                                                    <p class="card-text">{{$item->category->name}}</p>
                                                                <hr>
                                                                <h5 class="card-title">{{__("Item Description")}}</h5>
                                                                    <p class="card-text">{{$item->getDescriptionAttribute()}}</p>
                                                                <hr>
                                                                <h5 class="card-title">{{__("Tax")}}</h5>
                                                                    <p class="card-text">% {{$item->tax}}</p>
                                                                <hr>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__("Close")}}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </td>

                                    <td><a href="{{route("admin.items.add_ons.index",["item_id" => $item->id])}}" class="btn btn-primary">{{__("Control")}}</a></td>


                                    <td>
                                        <a href="{{route("admin.items.edit", $item->id)}}" class="control-link edit"><i class="fas fa-edit"></i></a>
                                        <form action="{{route("admin.items.destroy", $item->id)}}" method="post" id="delete{{$item->id}}" style="display: none" data-swal-title="{{__("Delete Item")}}" data-swal-text="{{__("Are Your Sure To Delete This Item ?")}}" data-yes="{{__("Yes")}}" data-no="{{__("No")}}" data-success-msg="{{__("the item has been deleted successfully")}}">@csrf @method("delete")</form>
                                        <span href="#" class="control-link remove form-confirm" data-form-id="#delete{{$item->id}}"><i class="far fa-trash-alt"></i></span>
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
