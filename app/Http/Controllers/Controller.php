<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function insert(){
        $user = User::find(1);
        $user->rateable()->create([
            "rated_id"=>2,
            "rate"=>4.5,
            "note"=>"NOTE From User",
        ]);
        return "success";
    }

    public function UserDoctorRates(){
        $user = Consultant::find(1);

        return $user->rateable;
    }


}
