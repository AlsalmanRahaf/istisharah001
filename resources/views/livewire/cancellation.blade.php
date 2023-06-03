<div>
    @if(Session::has('collision'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{Session::get('collision') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <select class="status form-control" wire:change="changeCancellation" wire:model="cancellationStatus">
        <option value="{{$cancellationStatus}}" selected>{{\Illuminate\Support\Facades\App::getLocale() == 'en' ? ($cancellationStatus == 1 ? 'Cancelled' : 'Un Cancelled') :  ($cancellationStatus == 1 ? 'ملغي' : 'غير ملغي')}}</option>
        @if($cancellationStatus == 1)
            <option value="3">{{\Illuminate\Support\Facades\App::getLocale() == 'en' ? 'Un Cancelled' : 'غير ملغي' }}</option>
        @elseif($cancellationStatus == 0 || $cancellationStatus == 3)
            <option value="1" id="exampleModal">{{\Illuminate\Support\Facades\App::getLocale() == 'en' ? 'Cancelled': 'ملغي' }}</option>
        @endif
    </select>

        <div class="modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog" role="document" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{__('Cancelled Reason')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="hide()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="Cancelled-Reason" class="col-form-label">{{__('Cancelled Reason')}}:</label>
                                <input type="text" class="form-control" id="Cancelled-Reason" wire:model="cancelled_reason" style="border: 1px solid black"><br/>
                                <p id="error" style="display: none;color: red">
                                    {{__('The Cancelled Reason Should Not Be Empty')}}</p>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="hide()" style="margin: 5px">
                            {{__('Close')}}</button>
                        <button type="button" class="btn btn-primary" onclick="save()" style="margin: 5px">
                            {{__('Save')}}</button>
                    </div>
                </div>
            </div>
        </div>
</div>