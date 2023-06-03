@extends("layouts.admin.app")
@section("page-title")
    {{__("Edit Welcome")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> Welcome Message Edit</h1>
            <p>Control Promo Code </p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="{{route("admin.WelcomeMessage.index")}}"> Welcome Message</a></li>
            <li class="breadcrumb-item"><a href="#">Edit</a></li>
        </ul>
    </div>
@endsection
@section("content")
    <div class="row">
        <div class="col-lg-10 m-auto">
            <div class="tile">
                <h3 class="tile-title">Edit  Welcome Message</h3>
                <div class="tile-body">
                    

                    
                    <form method="post" action="{{route("admin.WelcomeMessage.update", $WelcomeMessage->id)}}" enctype="multipart/form-data">
                        @csrf
                        @method("put")
                        <div class="row">
             
                            

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="control-label">English Message</label>
                                <input class="form-control @if($errors->has('TextMessage_En')) is-invalid @endif" type="text" name="TextMessage_En" placeholder="Enter title" value="{{inputValue("TextMessage_En",$WelcomeMessage)}}">
                            </div>
                            @error("TextMessage_En")
                            <div class="input-error">{{$message}}</div>
                            @enderror
                           
                        </div>    
    
                
    
                            
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="control-label">Arbic Message</label>
                                <input class="form-control @if($errors->has('TextMessage_Ar')) is-invalid @endif" type="text" name="TextMessage_Ar" placeholder="Enter title" value="{{inputValue("TextMessage_Ar",$WelcomeMessage)}}">
                            </div>
                            @error("TextMessage_Ar")
                            <div class="input-error">{{$message}}</div>
                            @enderror
                        
                        </div>

                            
                            
                            
                    
                         <div class="col-lg-12">
                        <div class="tile-footer">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Edit</button>
                        </div>
                        </div>
                        
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
