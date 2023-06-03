@extends("layouts.admin.app")
@section("page-title")
    Dashboard Firewall
@endSection

@section('content')

    <div class="row mt-5">
        <div class="col-lg-6  m-auto">
            <div class="tile">
                <div class="tile-body">
      <div class="login-box">
          <form class="login-form" autocomplete="off" method="post" action="{{route("admin.admins.check",["route"=>$route])}}">
            @csrf
              <input class="form-control" type="hidden" autocomplete="off" >

              <div class="form-group">
                <label class="control-label">USER NAME</label>
                <input class="form-control" type="text" placeholder="Email" name="user_name" id="user_name" autofocus autocomplete="off">
            </div>
            <div class="form-group">
                <label class="control-label">PASSWORD</label>
                <input class="form-control" type="password" placeholder="Password" name="password" id="password" autocomplete="off">
            </div>
            <div class="form-group btn-container">
                <button class="btn btn-primary btn-block"><i class="fa fa-sign-in fa-lg fa-fw"></i>SIGN IN</button>
            </div>

              @if(count($errors) > 0 )
                  <div class="login-errors">
                      <ul>
                          <li>Username or password incorect</li>
                      </ul>
                  </div>
              @endif
        </form>
    </div>

                </div></div></div></div>

@endsection



