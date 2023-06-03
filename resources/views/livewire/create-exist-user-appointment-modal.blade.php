<div wire:ignore.self class="modal fade" id="ExistUserAppointment" tabindex="-1" aria-labelledby="ExistUserAppointment" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title fs-5" id="exampleModalLabel">{{__('Create Appointment For Exist User')}}</h3>
            </div>
            <form wire:submit.prevent="store" id="form">
                <div class="modal-body">
                    <div class="col-sm-12">
                        <p>{{__('Choose User')}}</p>
                        <div class="form-group">
                            <select wire:model="user" wire:change="changeDoctor" id="users" class="selectpicker form-control @if($errors->has('user')) is-invalid @endif" data-live-search="true"  style="background: none;padding: 10px;border-radius: 7px;height: 45px;" >
                                <option value="" wire:key="default">-- {{__('Select User')}} --</option>
                                @if($users)
                                    @foreach($users as $user)
                                        @if($user->full_name)
                                        <option value="{{$user->id}}" wire:key="user-{{$user->id}}" selected>{{$user->full_name}}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                            <div>
                                @error('user') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div style="display: flex;margin-bottom: 15px">
                        <div class="col-sm-5">
                            <p>{{__('Online/Direct')}}</p>
                            <select  class="form-control @if($errors->has('is_online'))is-invalid @endif" id="is_online" wire:model="is_online" wire:change="onlineOrNot" style="background: none;height: 45px;padding: 10px;border-radius: 7px">
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
                            <p>{{__('Choose Doctor')}}</p>
                            <select wire:model="object_id" wire:change="handleTime"  class="form-control @if($errors->has('object_id')) is-invalid @endif" style="margin: 5px 0px;height: 45px;background: none;padding:13px;border-radius: 7px" >
                                <option value="" wire:key="default">-- {{__('Select Doctor')}} --</option>
                                @if($doct)
                                    @foreach($doct as $index => $doctor)
                                        <option value="{{$doctor->object_id}}" wire:key="doctor-{{$doctor->object_id}}">{{ $doctor->full_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <div>
                                @error('object_id') <span class="text-danger">{{__('The doctor field is required.')}}</span> @enderror
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
                                        <input id="slotTime{{$slot[0]}}" value="{{$slot[1]}}"  type="radio" wire:change="done" wire:model="slotTime" disabled>
                                        <div style="display: flex;flex-direction:column;padding:5px 10px;margin:2px;align-items: center;background: rgba(187,187,187,0.29);border-radius: 7px" >
                                            <i class="fa fa-ban" aria-hidden="true" style="font-size:40px;z-index: 100"></i>
                                            <label style="margin-top:-30px" for="slotTime{{$slot[0]}}">{{ date('h:i',strtotime($slot[1]))}} - {{date('h:i',strtotime($slot[2])) }}</label>
                                        </div>
                                    @else
                                        <input id="slotTime{{$slot[0]}}" value="{{$slot[1]}}"  type="radio" wire:change="done" wire:model="slotTime">
                                        <div class="radio-tile" style="padding: 10px;margin:2px">
                                            <label for="slotTime{{$slot[0]}}">{{ date('h:i',strtotime($slot[1]))}} - {{date('h:i',strtotime($slot[2])) }}</label>
                                        </div>
                                    @endif
                                @elseif($slot[3] === 'dis')
                                    <input id="slotTime{{$slot[0]}}" value="{{$slot[1]}}"  type="radio" wire:change="done" wire:model="slotTime" disabled>
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
                        <div style="display: flex;flex-direction:column;margin:5px 35%">
                                <img src="{{asset("uploads/time/70893-time.gif")}}" alt=""   width="150px" height="150px">
                                <br>
                        </div>
                        <h6 style="color:darkred;text-align: center;margin-left:21%">{{__("This doctor is not available on this day")}}</h6>

                    @endif
                </div>
                @if($createFlag)
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">{{__("Create")}}</button>
                    </div>
                @else
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger" disabled>{{__("Create")}}</button>
                    </div>
                @endif
            </form>

        </div>
    </div>
</div>
@section("scripts")

    <script type="text/javascript">
        $(function() {
            $('#selectAllDays').click(function() {
                if ($(this).prop('checked')) {
                    $('.form-check').prop('checked', true);
                } else {
                    $('.form-check').prop('checked', false);
                }
            });
        });
        window.addEventListener('close-exist-user-modal', event => {
            $('#ExistUserAppointment').modal('hide');
        })
    </script>
@endsection
