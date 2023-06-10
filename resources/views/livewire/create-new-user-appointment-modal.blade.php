<div wire:ignore.self class="modal fade" id="NewUserAppointment" tabindex="-1" aria-labelledby="NewUserAppointment" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title fs-5" id="exampleModalLabel">{{__('Create Appointment For New User')}}</h3>
            </div>
            <form wire:submit.prevent="store">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label">{{__('Full name')}}</label>
                        <input class="form-control @if($errors->has('full_name')) is-invalid @endif" type="text" wire:model="full_name" placeholder="Enter Full name" style="height: 45px;"/>
                        @error('full_name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="control-label">{{__('Phone number')}}</label>
                        <div style="display: flex;align-items: center">
                            <span style="border: 1px solid black;padding: 10px">+962</span><input class="form-control @if($errors->has('phone')) is-invalid @endif" pattern="+962" type="text" wire:model="phone"  placeholder="Enter Phone Number" style="height: 45px;"/>
                        </div>
                        @if(Session::has('existUser'))
                            <p class="text-danger">{{Session::get('existUser') }}</p>
                        @endif
                        @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div style="display: flex;margin-bottom: 15px">
                        <div class="col-sm-5">
                            <p>{{__('Online/Direct')}}</p>
                            <select  class="form-control @if($errors->has('is_online'))is-invalid @endif" wire:model="is_online" wire:change="onlineOrNot" style="background: none;height: 45px;padding: 10px;border-radius: 7px">
                                <option value="0" >-- {{__('IS Online ?')}} --</option>
                                <option value="1">{{__('Yes')}}</option>
                                <option value="0">{{__('No')}}</option>
                            </select>
                            <div>
                                @error('is_online') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div style="display: flex;flex-wrap: wrap">
                        <div class="col-sm-6" style="margin-bottom:10px">
                            <p>{{__('Choose Booking Date')}}</p>
                            @php
                                $current = \Carbon\Carbon::now();
                            @endphp

                            <input type="date" class="form-control @if($errors->has('user_object_booking')) is-invalid @endif"  min="{{$current->toDateString()}}"  id="timeDate"  wire:change="SetClicked" wire:model="user_object_booking"  style="background: none;padding:10px;border-radius: 7px;height: 45px">
                            @error('user_object_booking') <span class="text-danger">{{__('The user booking date field is required.')}}</span> @enderror

                        </div>
                        <div class="col-sm-6" style="margin-bottom:10px">
                            <p>{{__('Choose consultant')}}</p>
                            <select wire:model="object_id" wire:change="handleTime"  class="form-control @if($errors->has('object_id')) is-invalid @endif" style="margin: 5px 0px;height: 45px;background: none;padding:13px;border-radius: 7px" >
                                <option value="">-- {{__('Select consultant')}} --</option>
                                @if($doct)
                                    @foreach($doct as $index => $consultant)
                                        <option value="{{$consultant->object_id}}">{{ $consultant->full_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <div>
                                @error('object_id') <span class="text-danger">{{__('The consultant field is required.')}}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12" id="bookingTime" style="display: flex;flex-wrap:wrap;width: 100%">
                    <style>
                        * {
                            box-sizing: border-box;
                            font-family: sans-serif;
                        }

                        body {
                            background-color: #cc160d;
                        }
                        .input-container {
                            position: relative;
                            display: flex;
                        }

                        .input-container input {
                            position: absolute;
                            height: 100%;
                            width: 100%;
                            margin: 0;
                            cursor: pointer;
                            z-index: 2;
                            opacity: 0;
                        }

                        .input-container .radio-tile {
                            display: flex;
                            flex-direction: row;
                            align-items: center;
                            justify-content: center;
                            height: 100%;
                            border:1px solid #cc160d;
                            border-radius: 4px;
                            transition: all 10ms ease;
                        }

                        .input-container ion-icon {
                            color: #cc160d;
                            font-size: 3rem;
                        }

                        .input-container label {
                            color: #cc160d;
                            font-size: 0.80rem;
                            font-weight: 600;
                            text-transform: uppercase;
                            letter-spacing: 1px;
                        }

                        input:checked+.radio-tile {
                            background-color: #cc160d;
                            box-shadow: 0 0 4px #cc160d;
                            transform: scale(1.1);
                        }

                        input:hover+.radio-tile {
                            box-shadow: 0 0 4px #cc160d;
                        }

                        input:checked+.radio-tile ion-icon,
                        input:checked+.radio-tile label {
                            color: white;
                        }
                    </style>
                    @if($arrayFilter)
                        @foreach($arrayFilter as $index => $slot)
                            <div class="input-container" style="margin: 5px;display: flex" >
                                @if($slot[3] === '')
                                    @if($dt->toDateString() == $this->date && ($dt->toTimeString() >= date('H:i:s',strtotime($slot[1]))))
                                        <input id="slotTime{{$slot[0]}}" value="{{$slot[1]}}"  type="radio" wire:model="slotTime" wire:change="done" disabled>
                                        <div style="display: flex;flex-direction:column;padding:5px 10px;margin:2px;align-items: center;background: rgba(187,187,187,0.29);border-radius: 7px" >
                                            <i class="fa fa-ban" aria-hidden="true" style="font-size:40px;z-index: 100"></i>
                                            <label style="margin-top:-30px" for="slotTime{{$slot[0]}}">{{ date('h:i',strtotime($slot[1]))}} - {{date('h:i',strtotime($slot[2])) }}</label>
                                        </div>
                                    @else
                                        <input id="slotTime{{$slot[0]}}" value="{{$slot[1]}}"  type="radio" wire:model="slotTime" wire:change="done">
                                        <div class="radio-tile" style="padding: 10px;margin:2px">
                                            <label for="slotTime{{$slot[0]}}">{{ date('h:i',strtotime($slot[1]))}} - {{date('h:i',strtotime($slot[2])) }}</label>
                                        </div>
                                    @endif
                                @elseif($slot[3] === 'dis')
                                    <input id="slotTime{{$slot[0]}}" value="{{$slot[1]}}"  type="radio" wire:model="slotTime" wire:change="done" disabled>
                                    <div style="display: flex;flex-direction:column;padding:5px 10px;margin:2px;align-items: center;background: rgba(187,187,187,0.29);border-radius: 7px" >
                                        <i class="fa fa-ban" aria-hidden="true" style="font-size:40px;z-index: 100"></i>
                                        <label style="margin-top:-30px" for="slotTime{{$slot[0]}}">{{ date('h:i',strtotime($slot[1]))}} - {{date('h:i',strtotime($slot[2])) }}</label>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                        <div>
                            @error('slotTime') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    @elseif($flag==true)
                        @if(\Illuminate\Support\Facades\App::getLocale() == 'en')
                            <div style="display: flex;flex-direction:column;margin:5px 35%">
                                <img src="{{asset("uploads/time/70893-time.gif")}}" alt=""   width="150px" height="150px">
                                <br>
                            </div>
                            <h6 style="color:darkred;text-align: center;margin-left:21%">{{__('This consultant is not available on this day')}}</h6>
                        @else
                            <div @if(\Illuminate\Support\Facades\App::getLocale() == 'ar') style="display: flex;flex-direction:column;margin: 0px auto" @endif>
                                <img src="{{asset("uploads/time/70893-time.gif")}}" alt=""   width="150px" height="150px">
                                <br>
                                <h6 style="color:darkred;text-align: center;margin-left:21%">{{__('This consultant is not available on this day')}}</h6>
                            </div>
                        @endif
                    @endif
                </div>
                @if($createFlag)
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">{{__('Create')}}</button>
                    </div>
                @else
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger" disabled>{{__('Create')}}</button>
                    </div>
                @endif
            </form>

        </div>
    </div>
</div>
@section("scripts")
    <script>
        window.addEventListener('close-modal', event => {
            $('#NewUserAppointment').modal('hide');
        })
    </script>

@endsection
