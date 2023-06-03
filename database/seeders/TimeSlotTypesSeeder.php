<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimeSlotTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            ['description' => "دوام حجز أطباء أثنين وأربعاء", 'slot_duration' => "15 min", 'time_from' => "09:00:00", 'time_to' => "10:00:00"],
            ['description' => "دوام حجز أطباء أحد وثلاثاء وخميس", 'slot_duration' => "30 min", 'time_from' => "09:00:00", 'time_to' => "16:00:00"],
            ['description' => "دوام حجز أطباء يومياً", 'slot_duration' => "30 min", 'time_from' => "10:00:00", 'time_to' => "13:00:00"],
        ];
        DB::table('time_slot_types')->insert($types);
    }
}
