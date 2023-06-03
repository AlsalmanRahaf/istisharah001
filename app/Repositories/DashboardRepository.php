<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class DashboardRepository
{
    public function getAllCounters()
    {
        $sql = "SELECT  (SELECT COUNT(id) FROM users WHERE users.type = 'u') as users,
                        (SELECT COUNT(id) FROM users WHERE users.type = 'c') as consultants,
                        (SELECT COUNT(id) FROM admins WHERE admins.deleted_at IS NULL) as admins,
                        (SELECT COUNT(id) FROM users WHERE users.type = 'cph') as consultantsPharmacist,
                        (SELECT COUNT(id) FROM users WHERE users.type = 'cn') as consultantsNutrition,
                        (SELECT COUNT(id) FROM users WHERE users.type = 'cd') as consultantDiabetes,
                        (SELECT COUNT(id) FROM ads ) as advertisements,
                        (SELECT COUNT(id) FROM social_media ) as social_media,
                        (SELECT COUNT(id) FROM sliders ) as sliders,
                        (SELECT COUNT(id) FROM users WHERE users.type = 'a') as Appadmins,
                        (SELECT COUNT(id) FROM custom_consultations) as Other_consultations 


 ";
        return DB::select($sql)[0];
    }

}
