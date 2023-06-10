@extends("layouts.admin.app")
@section("page-title")

@endSection
@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>{{__("Requests Join")}}</h1>
            <p>{{__("All Requests Join")}}</p>
        </div>

        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Dashboard")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("Requests Join")}}</a></li>

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
                                <th>{{__("ID")}}</th>
                                <th>{{__("name")}}</th>
                                <th>{{__("email")}}</th>
                                <th>{{__("phone")}}</th>
                                <th>{{__("Commercial Register Image")}}</th>
                                <th>{{__("Created at")}}</th>

                            </tr>
                            </thead>
                            <tbody>

                            <?php $serial_number = 1; ?>
                            @foreach($consultantrequests as $data)
                                <tr>
                                    <td>{{$data->id}}</td>
                                    <td>{{$data->name}}</td>
                                    <td>{{$data->email}}</td>
                                    <td>{{$data->phone}}</td>
                                    <td>
                                        @if($data->getFirstMediaFile("Consultant Documents"))

                                            <button type="button" class="btn btn-primary " data-toggle="modal"
                                                    data-target="#Attachment{{$data->id}}">
                                                {{__('View')}}
                                            </button>
                                            <div class="modal fade" id="Attachment{{$data->id}}" tabindex="-1"
                                                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-xl">
                                                    <div class="modal-content" style="height: 800px;overflow-y: auto;">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="exampleModalLabel">{{__('Attachments')}}</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body mt-2 mb-2">
                                                            <div class="links-box">
                                                                <div class="row">
                                                                    @if($data->getFirstMediaFile("Consultant Documents"))
                                                                        @foreach($data->getFirstMediaFile("Consultant Documents") as $img)
                                                                       <div class="mr-5">
                                                                           <img src="{{$img->url}}" class="img-fluid">
                                                                        {{ dd($img->file->getClientMimeType())}};
}}



                                                                       </div>

                                                                                <div>
                                                                                <a target="_blank"
                                                                                   href="{{ asset($img->path.'/'.$img->filename)}}"><span
                                                                                            class="field-view">{{"$img->filename"}}</span></a>
                                                                                </div>
{{--                                                                            @endif--}}
                                                                        @endforeach
                                                                    @endif

                                                                    {{--                                                                @if($data->getFirstMediaFile("Consultant Documents"))--}}
                                                                    {{--                                                                    @foreach($data->getFirstMediaFile("Consultant Documents") as $img)--}}

                                                                    {{--                                                                        @if( $img== 'image/*')--}}
                                                                    {{--                                                                            <img src="{{$img->url}}" }}>--}}

                                                                    {{--                                                                        @else--}}
                                                                    {{--                                                                            <a target="_blank"--}}
                                                                    {{--                                                                               href="{{ asset($img->path.'/'.$img->filename)}}"><span--}}
                                                                    {{--                                                                                        class="field-view">{{$img->filename}}</span></a>--}}

                                                                    {{--                                                                            <div class="download-links-box">--}}
                                                                    {{--                                                                                <div class="image">--}}

                                                                    {{--                                                                                    @endif--}}
                                                                    {{--                                                                                </div>--}}
                                                                    {{--                                        @endforeach--}}

                                                                    {{--                                    @endif--}}
                                                                    {{--                                                                            </div>--}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>

                                    <td>{{$data->created_at->diffForHumans()}}</td>
                                    @if(is_null($data->accept))
                                        <td>
                                             <div class="mb-3">
                                            <form action="{{route("admin.accept", ["id" => $data->id])}}" method="post">
                                                @csrf
                                                <input type="hidden" value="1" name="accept">

                                                <button type="button" class="btn btn-success accept"
                                                        onclick="this.form.submit()">
                                                    {{__("Accept")}}
                                                </button>
                                            </form>
                                             </div>

                                            <form action="{{route("admin.accept", ["id" => $data->id])}}" method="post">
                                                @csrf
                                                <input type="hidden" value="0" name="accept">

                                                <button type="button" class="btn btn-primary accept"
                                                        onclick="this.form.submit()">
                                                    {{__("NOT Accept")}}
                                                </button>
                                            </form>

                                        </td>
                                    @elseif($data->accept ==1)

                                        <td>
                                            <button type="button" class="btn btn-success accept">
                                                {{__("Accepted")}}
                                            </button>
                                        </td>
                                    @elseif($data->accept==0)
                                        <td>
                                            <button type="button" class="btn btn-primary accept">
                                                {{__("NOT Accepted")}}
                                            </button>


                                        </td>
                                    @endif


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
    <script type="text/javascript">$('#sampleTable').DataTable({
            "order": [[0, "desc"]]
        });</script>
    @if(session()->has("technician_register_info"))
        <script>
            $("#Register").modal('show')
        </script>
    @endif
    <script type="text/javascript">
        $(".download-excel").click(function (e) {
            e.preventDefault();

            let status = $("#status-filter option:selected").data("value");


            $(this).siblings("input[name='status']").val(status);


            $("#form-excel").submit();


        });

    </script>
    <script type="module" src="{{asset("assets/js/pages/technicians.js")}}"></script>

@endsection
