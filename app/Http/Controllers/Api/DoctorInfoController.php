<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BookingRating;
use App\Models\Doctor;
use App\Models\Specialization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\IntegrationTrait;
use Kreait\Firebase\Util\JSON;

class DoctorInfoController extends Controller
{
    public function index(Request $request){
        if ($request->doctorID){
            $doctorInfo = Doctor::where('object_id',$request->doctorID)->first();
            if ($doctorInfo) {
                $doctorImage = $doctorInfo->getFirstMediaFile('Doctors')->url ?? 'no doctor image';
                return response()->json(['data' => $doctorInfo,'image' => $doctorImage])->send();
            }
        }
    }
}
