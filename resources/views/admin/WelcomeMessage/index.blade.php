@extends("layouts.admin.app")
@section("page-title")
    {{__("Welcome message")}}
@endSection


@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>Welcome Message</h1>
            <p>Control and view all promo code</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">welcome message</a></li>
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
                                <th>Arbic Message </th>
                                <th>English Message </th>
                                <th>Control</th>
                            </tr>
                        </thead>
                        <tbody>
                            
            
                        @foreach($WelcomeMessages as $WelcomeMessage)
                            <tr>
                                <td>{{$WelcomeMessage->TextMessage_Ar}}</td>
                                <td>{{$WelcomeMessage->TextMessage_En}}</td>
                             
                                
                                <td>
                                 <a href="{{route("admin.WelcomeMessage.edit", $WelcomeMessage->id)}}" class="control-link edit"><i class="fas fa-edit"></i></a>
                                <form action="{{route("admin.WelcomeMessage.destroy", $WelcomeMessage->id)}}" method="post" id="delete{{$WelcomeMessage->id}}" style="display: none" data-swal-title="Delete Welcome Message" data-swal-text="Are Your Sure To Delete This Welcome Message ?" data-yes="Yes" data-no="No" data-success-msg="the Welcome Message has been deleted succssfully">@csrf @method("delete")</form>
                                <span href="#" class="control-link remove form-confirm" data-form-id="#delete{{$WelcomeMessage->id}}"><i class="far fa-trash-alt"></i></span>
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
    <!-- Google analytics script-->

@endsection
