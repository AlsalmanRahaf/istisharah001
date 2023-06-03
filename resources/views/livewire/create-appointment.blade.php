<div style="display: flex;margin-top: 20px">
    <div class="col-sm-2">
        @php
            $current = \Carbon\Carbon::now();
        @endphp
        <input type="date" min="{{$current->toDateString()}}" id="timeDate" wire:model="object_booking" wire:change="handleTime" name="date" style="background: none;padding:7px;border-radius: 7px">
    </div>
    <div class="col-sm-2" id="bookingTime">
        <select class="form-select" name="slotTime" aria-label="Default select example" style="background: none;padding: 10px;border-radius: 7px">
            <option selected disabled>Select Booking Day</option>
            @if ($slot_time)
                @foreach($slot_time as $slot)
                    @php
                        $object_week_days = \App\Models\ObjectWeekDays::where('id',$slot->object_week_days_id)->first();
                    @endphp
                    @if($object_week_days->week_day_en_name == $days)
                        <option value="{{$slot->id}}">{{ date('h:i',strtotime($slot->time_from))}} {{date('h:i',strtotime($slot->time_to)) }}</option>
                    @endif
                @endforeach
            @endif
        </select>
    </div>
</div>
