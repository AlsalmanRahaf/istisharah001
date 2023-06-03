@extends("layouts.admin.app")

@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> {{__("Add On Category")}}</h1>
            <p>{{__("create new add on category")}}</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">{{__("Items")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{$item->name}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("Add Ons")}}</a></li>
            <li class="breadcrumb-item"><a href="#">{{__("Create")}}</a></li>
        </ul>
    </div>
@endsection
@section("content")
    <div class="row">
        <div class="col-lg-10 m-auto">
            <form method="post" action="{{route("admin.items.add_ons.store",["item_id" => $item->id])}}" enctype="multipart/form-data">
                @csrf
                <div class="tile">
                    <h3 class="tile-title">{{__("Create New Add On Category")}}</h3>
                    <div class="tile-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("English Name")}}</label>
                                    <input class="form-control @if($errors->has('add_ons_name_en')) is-invalid @endif" type="text" name="add_ons_name_en" placeholder="{{__("Enter English name")}}" value="{{inputValue("add_ons_name_en")}}">
                                </div>
                                @error("add_ons_name_en")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">{{__("Arabic Name")}}</label>
                                    <input class="form-control @if($errors->has('add_ons_name_ar')) is-invalid @endif" type="text" name="add_ons_name_ar" placeholder="{{__("Enter Arabic name")}}" value="{{inputValue("add_ons_name_ar")}}">
                                </div>
                                @error("add_ons_name_ar")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6" >
                                <div class="form-group">
                                    <label for="">{{__("Choice type")}}</label>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="customRadio1" name="choice_type" class="custom-control-input" value="1"  {{checked("choice_type", '1')}}>
                                        <label class="custom-control-label" for="customRadio1">{{__("Single")}}</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="customRadio2" name="choice_type" class="custom-control-input" value="2" {{checked("choice_type", '2')}}>
                                        <label class="custom-control-label" for="customRadio2">{{__("Multi")}}</label>
                                    </div>
                                </div>
                                @error("choice_type")
                                <div class="input-error">{{$message}}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>
                <div class="tile">
                    <h3 class="tile-title">{{__("Add Options")}}</h3>
                    <div class="tile-body options-container">
                        <div class="option-box">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label">{{__('English Name')}}</label>
                                        <input class="form-control @if($errors->has('add_ons_list_en.0')) is-invalid @endif" type="text" name="add_ons_list_en[]" placeholder="Enter English name" value="{{isset($options_selected) ? old("add_ons_list_en")[0] : null}}">
                                    </div>
                                    @error("add_ons_list_en.0")
                                    <div class="input-error">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label">{{__("Arabic Name")}}</label>
                                        <input class="form-control @if($errors->has('add_ons_list_en.0')) is-invalid @endif" type="text" name="add_ons_list_ar[]" placeholder="Enter Arabic name" value="{{isset($options_selected) ? old("add_ons_list_ar")[0] : null}}">
                                    </div>
                                    @error("add_ons_list_ar.0")
                                    <div class="input-error">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label">{{__("Adding Price")}}</label>
                                        <input class="form-control @if($errors->has('price.0')) is-invalid @endif" type="text" name="price[]" placeholder="Enter Price" value="{{isset($options_selected) ? old("price")[0] : null}}">
                                    </div>
                                    @error("price.0")
                                    <div class="input-error">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        @if(isset($options_selected))
                            @for($i=1; $i < $options_selected; $i++)
                                <div class="option-box">
                                    <div class='text-right mt-3 mb-3'>
                                        <span class='btn btn-primary remove-option'>{{__("Remove")}}</span>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="control-label">{{__("English Name")}}</label>
                                                <input class="form-control @if($errors->has('add_ons_list_en.' . $i)) is-invalid @endif" type="text" name="add_ons_list_en[]" placeholder="Enter English name" value="{{isset(old("add_ons_list_en")[$i]) ? old("add_ons_list_en")[$i] : null}}">
                                            </div>
                                            @error("add_ons_list_en." . $i)
                                            <div class="input-error">{{$message}}</div>
                                            @enderror
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="control-label">{{__("Arabic Name")}}</label>
                                                <input class="form-control @if($errors->has('add_ons_list_en.' . $i)) is-invalid @endif" type="text" name="add_ons_list_ar[]" placeholder="Enter Arabic name" value="{{isset(old("add_ons_list_ar")[$i]) ? old("add_ons_list_ar")[$i] : null}}">
                                            </div>
                                            @error("add_ons_list_ar." . $i)
                                            <div class="input-error">{{$message}}</div>
                                            @enderror
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="control-label">{{__("Adding Price")}}</label>
                                                <input class="form-control @if($errors->has('price.' . $i)) is-invalid @endif" type="text" name="price[]" placeholder="Enter Price" value="{{isset(old("price")[$i]) ? old("price")[$i] : null}}">
                                            </div>
                                            @error("price." . $i)
                                            <div class="input-error">{{$message}}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        @endif
                    </div>
                    <div class="col-lg-12">
                        <span class="btn btn-primary add-option"><i class="fas fa-plus"></i>  {{__("Add Option")}}</span>
                    </div>
                    <div class="tile-footer">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>{{__("Create")}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section("scripts")
    <script type="text/javascript">
        $(".add-option").on("click",function(){
            let container = $(".options-container");
            let row = $("<div class='row'></div>");
            let box = $("<div class='option-box'></div>");


            let group1 = $("<div class='form-group'></div>");
            group1.append($("<label class='control-label'></label>").text("English name"));
            group1.append($('<input class="form-control" type="text" name="add_ons_list_en[]" placeholder="Enter English name"">'));
            row.append($("<div class='col-lg-6'></div>").append(group1));


            let group2 = $("<div class='form-group'></div>");
            group2.append($("<label class='control-label'></label>").text("Arabic name"));
            group2.append($('<input class="form-control" type="text" name="add_ons_list_ar[]" placeholder="Enter Arabic name"">'));
            row.append($("<div class='col-lg-6'></div>").append(group2));

            let group3 = $("<div class='form-group'></div>");
            group3.append($("<label class='control-label'></label>").text("Adding Price"));
            group3.append($('<input class="form-control" type="text" name="price[]" placeholder="Enter Adding Price"">'));
            row.append($("<div class='col-lg-6'></div>").append(group3));

            box.append($("<div class='text-right mt-3 mb-3'></div>").
            append($("<span class='btn btn-primary remove-option'>Remove</span>")));
            box.append(row);
            container.append(box.append(box));
            container.append($("hr"));
        });

        $(document).on("click",".remove-option",function (){
            $(this).parents(".option-box").remove();
        });
    </script>

@endsection
