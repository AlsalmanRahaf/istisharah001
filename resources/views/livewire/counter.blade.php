<div class="d-flex flex-lg-row flex-sm-column flex-md-column justify-content-lg-around justify-content-sm-centers flex-wrap ">
        <div class="col-sm-12">
            <p>{{__('Online/Direct')}}</p>
            <select  class="form-control @if($errors->has('is_online'))is-invalid @endif" id="is_online"   name="is_online"  wire:change="onlineOrNot" wire:model="is_online" style="background: none;height: 45px;padding: 10px;border-radius: 7px">
                @if($user_object_booking1->is_online == 1)
                    @if($onlineFlag == true)<option value="1" selected>{{__('Online')}}</option>@endif
                    <option value="0">{{__('Direct')}}</option>
                @else
                    <option value="0">{{__('Direct')}}</option>
                    @if($onlineFlag == true)<option value="1">{{__('Online')}}</option>@endif
                @endif
            </select>
            <div>
                @error('is_online') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
            <div class="col-sm-6 mt-3" style="margin-bottom:10px">
                <p>{{__('Choose Booking Date')}}</p>
                @php
                 $current = \Carbon\Carbon::now();
                @endphp
                <input type="date" class="form-control @if($errors->has('user_object_booking')) is-invalid @endif"  min="{{$current->toDateString()}}"   id="timeDate"  wire:change="SetClicked" value="{{$this->user_object_booking}}"  wire:model="user_object_booking"  name="user_object_booking"   style="background: none;padding:10px;border-radius: 7px;height: 45px">
{{--                @error('user_object_booking') <span class="text-danger">{{__('The user booking date field is required.')}}</span> @enderror--}}
            </div>
            <div class="col-sm-6 mt-3">
                <p>{{__('Choose Doctor')}}</p>
                <select wire:model="object_id" name="object_id" wire:change="handleTime"  class="form-control @if($errors->has('object_id')) is-invalid @endif" style="margin: 5px 0px;height: 45px;background: none;padding:13px;border-radius: 7px" >
                    <option value="{{$user_doctor->object_id}}" wire:key="doctor-{{$user_doctor->object_id}}" selected>{{$user_doctor->full_name}}</option>
                    @if($doct)
                        @foreach($doct as $index => $doctor)
                            @if($doctor->object_id != $user_doctor->object_id)
                            <option value="{{$doctor->object_id}}" wire:key="doctor-{{$doctor->object_id}}">{{ $doctor->full_name }}</option>
                            @endif
                        @endforeach
                    @endif
                </select>
                <div>
{{--                    @error('object_id') <span class="text-danger">{{__('The doctor field is required.')}}</span> @enderror--}}
                </div>
            </div>
    <div class="col-sm-12" id="bookingTime">
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
            <div class="d-flex flex-wrap">
                @if($this->date >= $dt->toDateString())
                    @foreach($arrayFilter as $index => $slot)
                        <div class="input-container" style="margin: 5px" >
                        @if($slot[3] === '')
                            @if($dt->toDateString() == $this->date && ($dt->toTimeString() >= date('H:i:s',strtotime($slot[1]))))
                            <input id="slotTime{{$slot[0]}}" value="{{$slot[1]}}"  type="radio" wire:change="done" wire:model="slotTime" name="slotTime" disabled>
                            <div style="display: flex;flex-direction:column;padding:5px 10px;margin:2px;align-items: center;background: rgba(187,187,187,0.29);border-radius: 7px" >
                                <i class="fa fa-ban" aria-hidden="true" style="font-size:40px;z-index: 100"></i>
                                <label style="margin-top:-30px" for="slotTime{{$slot[0]}}">{{ date('h:i',strtotime($slot[1]))}} - {{date('h:i',strtotime($slot[2])) }}</label>
                            </div>
                            @else
                            <input id="slotTime{{$slot[0]}}" value="{{$slot[1]}}"  type="radio" wire:change="done" wire:model="slotTime" name="slotTime">
                            <div class="radio-tile" style="padding: 10px;margin:2px">
                                <label for="slotTime{{$slot[0]}}">{{ date('h:i',strtotime($slot[1]))}} - {{date('h:i',strtotime($slot[2])) }}</label>
                            </div>
                             @endif
                        @elseif($slot[3] === 'dis')
                            <input id="slotTime{{$slot[0]}}" value="{{$slot[1]}}"  type="radio" wire:change="done" wire:model="slotTime" name="slotTime" disabled>
                            <div style="display: flex;flex-direction:column;padding:5px 10px;margin:2px;align-items: center;background: rgba(187,187,187,0.29);border-radius: 7px" >
                                <i class="fa fa-ban" aria-hidden="true" style="font-size:40px;z-index: 100"></i>
                                <label style="margin-top:-30px" for="slotTime{{$slot[0]}}">{{ date('h:i',strtotime($slot[1]))}} - {{date('h:i',strtotime($slot[2])) }}</label>
                            </div>
                        @endif
                        </div>
                    @endforeach
                @else
                    <div class="d-flex justify-content-center flex-lg-column" style="margin:1% auto">
                        <img style="margin-left: 40px" src="{{asset("uploads/time/93992-expire.gif")}}" alt=""  width="100px" height="100px">
                        <h6 style="color:darkred;display: flex;justify-content: center">{{__('The appointment is expired')}}</h6>
                    </div>

                @endif
            </div>
            <div>
                @error('slotTime') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        @elseif($flag==true)
            <div class="d-flex justify-content-center">
                <img src="{{asset("uploads/time/70893-time.gif")}}" alt=""  width="150px" height="150px">
            </div>
            <h6 style="color:darkred;display: flex;justify-content: center">{{__('This doctor is not available on this day')}}</h6>
        @endif
        <div style="margin: 10px;background: #dc3545;color: white;width: 30%;border-radius:7px;padding: 10px">
            <h6>{{__('Don\'t Send Notification To :')}}</h6>
            <div style="display: flex;justify-content: space-around">
                @if($this->user_object_booking1->object_id != $this->object_id)
                <div class="form-check" style="padding: 5px;margin: 10px">
                    <input class="form-check-input" type="checkbox" id="check1" name="oldDoctor" value="oldDoctor">
                    <label class="form-check-label" @if(\Illuminate\Support\Facades\App::getLocale() == 'ar')style="margin:0px 15px" @endif>{{__('Old Doctor')}}</label>
                </div>
                <div class="form-check" style="padding: 5px;margin: 10px">
                    <input class="form-check-input" type="checkbox" id="check2" name="newDoctor" value="newDoctor">
                    <label class="form-check-label" @if(\Illuminate\Support\Facades\App::getLocale() == 'ar')style="margin:0px 15px" @endif>{{__('New Doctor')}}</label>
                </div>
                <div class="form-check" style="padding: 5px;margin: 10px">
                    <input class="form-check-input" type="checkbox" id="check3" name="patient" value="patient">
                    <label class="form-check-label" @if(\Illuminate\Support\Facades\App::getLocale() == 'ar')style="margin:0px 15px" @endif>{{__('Patient')}}</label>
                </div>
                @else
                    <div class="form-check" style="padding: 5px;margin: 10px">
                        <input class="form-check-input" type="checkbox" id="check1" name="doctor" value="doctor">
                        <label class="form-check-label" @if(\Illuminate\Support\Facades\App::getLocale() == 'ar')style="margin:0px 15px" @endif>{{__('Doctor')}}</label>
                    </div>
                    <div class="form-check" style="padding: 5px;margin: 10px">
                        <input class="form-check-input" type="checkbox" id="check3" name="patient" value="patient">
                        <label class="form-check-label" @if(\Illuminate\Support\Facades\App::getLocale() == 'ar')style="margin:0px 15px" @endif>{{__('Patient')}}</label>
                    </div>
                @endif
            </div>
        </div>
    </div>
        @if($createFlag)
            <div class="modal-footer" class="d-flex justify-content-end">
                <button class="btn btn-danger" type="submit" style="margin: 10px;display: flex;justify-content: flex-end">{{__('Save Changes')}}</button>
            </div>
        @else
            <div class="modal-footer" class="col-sm-12">
                <button class="btn btn-danger" disabled>{{__('Save Changes')}}</button>
            </div>
        @endif
</div>

