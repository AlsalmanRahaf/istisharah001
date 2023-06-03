@extends("layouts.admin.app")
@section("page-title")
    {{__("Dashboard")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>{{__("Promo Code")}}</h1>

        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("Promo Code")}}</a></li>
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
                        <table class="table table-hover table-bordered text-center" id="sampleTable">
                            <thead>
                            <tr>
                                <th>{{__("User Name")}}</th>
                                <th>{{__("User Phone")}}</th>
                                <th>{{__("Promo Code")}}</th>
                                <th>{{__("Points")}}</th>
                                <th>{{__("Show Used")}}</th>
                                <th>{{__("Control")}}</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($UserPromoCodes as $UserPromoCode)
                                <tr>
                                    <td>{{$UserPromoCode->user->full_name}}</td>
                                    <td>{{$UserPromoCode->user->phone_number}}</td>
                                    <td>{{$UserPromoCode->Promo_code}}</td>
                                    <td>{{$UserPromoCode->points}}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal{{$UserPromoCode->id}}" >
                                            {{__("Show")}}
                                        </button>
                                        <div class="modal fade" id="exampleModal{{$UserPromoCode->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Users Used</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body" id="selections" >
                                                        <div class="row">
                                                                <div class="col-lg mb-2 " data-type="ta" id="ta">
                                                                    <div class="tile">
                                                                        <div class="tile-body">
                                                                            <div class="table-responsive">
                                                                                <table class="table table-hover table-bordered text-center" id="sampleTable">
                                                                        <thead>
                                                                        <tr>
                                                                            <th>{{__("User Name")}}</th>
                                                                            <th>{{__("User Phone")}}</th>
                                                                            <th>{{__("Used At")}}</th>
                                                                        </tr>
                                                                        </thead>
                                                                        @foreach($UserPromoCode->UsesPromoCode as $user_use)
                                                                           <tr>
                                                                            <td>{{$user_use->users->full_name}}</td>
                                                                            <td>{{$user_use->users->phone_number}}</td>
                                                                            <td>{{$user_use->created_at->diffForHumans()}}</td>
                                                                           </tr>
                                                                        @endforeach

                                                                        <tbody>
                                                                        </tbody>
                                                                    </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                    <td>
                                        <form action="{{route("admin.promo-code.destroy", ["id" => $UserPromoCode->id])}}" method="post" id="delete{{$UserPromoCode->id}}" style="display: none" data-swal-title="{{__("Delete")}}" data-swal-text="{{__("Are Your Sure To Delete This Data ?")}}" data-yes="{{__("Yes")}}" data-no="{{__("No")}}" data-success-msg="{{__('the Data has been deleted successfully')}}">@csrf @method("delete")</form>
                                        <span href="#" class="control-link remove form-confirm" data-form-id="#delete{{$UserPromoCode->id}}"><i class="far fa-trash-alt"></i></span>
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
