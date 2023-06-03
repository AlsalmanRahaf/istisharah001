@extends("layouts.admin.app")

@section("page-nav-title")
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> Order Details</h1>
            <p>Control and view all Add Ons</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="{{route("admin.orders.index")}}">Orders</a></li>
            <li class="breadcrumb-item"><a href="#">{{$order->id}}</a></li>
            <li class="breadcrumb-item"><a href="#">Details</a></li>
        </ul>
    </div>
@endsection
@section("content")
    @include("includes.dialog")
    <div class="row">
        <div class="col-lg-12">
            <div class="tile">
                <div class="row">
                    <div class="col-lg-9">
                        <div class="order-info">
                            <label>Order number</label>
                            <div class="value">#{{$order->id}}</div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="order-info">
                            <label>Order Date</label>
                            <div class="value">{{$order->created_at->diffForhumans()}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-title">Items</div>
                <div class="tile-body">
                    @foreach($order->items as $item)
                    <div class="tile">
                        <div class="row">
                            <div class="col-lg-2">
                                <img src="{{$item->item_image}}" alt="" class="full-box-img">
                            </div>
                            <div class="col-lg-10">
                                <div class="row">
                                    <div class="col-lg-10">
                                        <h5><span style="color: var(--primary)">{{$item->quantity}}</span> * {{$item->item_name_en}}</h5>
                                        <h5>{{$item->item_name_ar}}</h5>
                                        @foreach($item->addOns as $addOn)
                                        <div class="mt-4">
                                            <h5>{{$addOn->AddOns_Category_name}}</h5>
                                            <div class="row">
                                                <div class="col-lg-7 text-size-17 color-sub font-weight-bold">
                                                    {{$addOn->AddOns_name}}
                                                </div>
                                                <div class="col-lg-5 color-primary text-size-16 ">
                                                    {{$addOn->AddOns_price}} JOD
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="order-info mb-3">
                                            <label style="font-size: 18px">Item Price</label>
                                            <div class="value" style="font-size: 16px">{{$item->itemPrice}} JOD</div>
                                        </div>
                                        <div class="order-info">
                                            <label style="font-size: 18px">Total Price</label>
                                            <div class="value" style="font-size: 16px">{{$item->totalPrice}} JOD</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
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
