@extends("layouts.admin.app")
@section("page-title")
    Dashboard
@endSection
@section("page-nav-title")
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> Dashboard</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Admins")}}</a></li>
            <li class="breadcrumb-item"><a href="#">index</a></li>
        </ul>
    </div>
@endsection

@section("content")
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">{{__('Edit app Setting')}}</h3>
                <div class="tile-body">
                    <form action="{{route('admin.App-Setting.update')}}" method="POST" >
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Terms_And_Conditions</label>
                                    <textarea type="text" name="Terms_And_Conditions"  rows="6" class="form-control  @error('Terms_And_Conditions') is-invalid @enderror"    placeholder="Enter full name" autocomplete="off">{{$Terms_And_Conditions}}</textarea>
                                    @error('Terms_And_Conditions')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="row">
                                <div class="form-group">
                                    <div class="col-lg-12 m-3">
                                        <label class="control-label">{{__("Promo code activation")}}</label>
                                               <select  name="contest" class="form-control">
                                                    <option  value="{{$contest}}">@if($contest==1){{__("Active")}}@else{{__("Inactive")}}@endif</option>
                                                    <option value="{{$contest==1?0:1}}" >@if($contest==1){{__("Inactive")}}@else{{__("Active")}}@endif</option>
                                                </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="tile-footer">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>{{__('Update')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
@endsection

@section("scripts")

@endsection
