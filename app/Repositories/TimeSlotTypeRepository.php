<?php

namespace App\Repositories;

use App\Models\CancelledBooking;
use App\Models\Doctor;
use App\Models\Notifications;
use App\Models\ObjectBooking;
use App\Models\TimeSlot;
use App\Models\User;
use App\Models\UserBookings;
use App\Models\UserDeviceToken;
use App\Traits\Firebase;
use App\Traits\IntegrationTrait;
use App\Traits\Notifications as NotificationsTrait;
use App\Traits\RepositoryTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TimeSlotTypeRepository
{
    public function index(){

    }
}
