@php $messageName = $messageName ?? "page-message";@endphp
@if(\Illuminate\Support\Facades\Session::has($messageName))
    @php $data = \Illuminate\Support\Facades\Session::get($messageName);@endphp
    <div class="alert alert-dismissible alert-{{$data["type"]}} page-message {{$data["type"]}}" >
        <button class="close" type="button" data-dismiss="alert">×</button>
        <p><strong class="alert-heading">{{$data["title"]}}</strong>! {{$data["body"]}}.</p>
    </div>
@endif
