@extends("layouts.admin.app")
@section("page-title")
    Dashboard
@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> Dashboard</h1>
            <p>profile Dashboard</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">index</a></li>
        </ul>
    </div>
@endsection

@section("content")
    <div class="row user">
        <div class="col-md-12">
            <div class="tab-content">
                <div class="tab-pane active" id="user-timeline">
                    <div class="timeline-post">
                        <div class="post-media"><a href="#"><img src="{{asset('assets/img/user_avatar.jpg')}}" style="height: 70px; width: 100px; border-radius: 50%; "></a>
                            <div class="content">
                                <h5><a href="#">Name :{{$Admin_info->full_name}}</a></h5>
                                <p class="text-muted">Email : {{$Admin_info->email}}</p>
                            </div>
                        </div>
                        <div class="post-content">
                            <p> </p>
                        </div>
                        <div class="tile-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered" id="sampleTable">
                                    <thead>
                                    <tr>
                                        <th>{{__("#")}}</th>
                                        <th>{{__("Role name")}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                    $i=0;
                                    @endphp
                                    @if($Admin_info->role != null)
                                            @foreach($Admin_info->role->permissions as $permission )
                                                <tr>
                                                    <td>{{++$i}}</td>
                                                   <td>{{$permission->name}}</td>
                                                </tr>
                                            @endforeach

                                    @elseif($Admin_info->is_super_admin == 1)
                                        <tr>
                                            <td>{{++$i}}</td>
                                            <td>Full permissions</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section("scripts")

    <script>

    </script>
@endsection
