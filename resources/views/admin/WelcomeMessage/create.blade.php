@extends("layouts.admin.app")
@section("page-title")
    {{__("Create Welcome")}}
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>craete  Welcome Message</h1>
    
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="{{route("admin.WelcomeMessage.index")}}">Welcome Message</a></li>
            <li class="breadcrumb-item"><a href="#">Create</a></li>
        </ul>
    </div>
@endsection
@section("content")
    <div class="row">
        <div class="col-lg-10 m-auto">
            <div class="tile">
                <h3 class="tile-title">Create Welcome Message</h3>
                <div class="tile-body">
                    

                    
                    <form method="post" action="{{route("admin.WelcomeMessage.store")}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">English Message</label>
                                    <input class="form-control @if($errors->has('TextMessage_En')) is-invalid @endif" type="text" name="TextMessage_En" placeholder="Enter title" value="{{inputValue("TextMessage_En")}}">
                                </div>
                                @error("TextMessage_En")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
 
                    
   
                                
                                                  <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">Arbic Message</label>
                                    <input class="form-control @if($errors->has('TextMessage_Ar')) is-invalid @endif" type="text" name="TextMessage_Ar" placeholder="Enter title" value="{{inputValue("TextMessage_Ar")}}">
                                </div>
                                @error("TextMessage_Ar")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>

                                
                            

                            
                           
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
