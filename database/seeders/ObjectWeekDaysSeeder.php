<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ObjectWeekDaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            ['time_slot_type_id'=>1, 'week_day_number' => 1, 'week_day_en_name' => "Monday", 'week_day_ar_name' => "الاثنين", 'is_off' => 0],
            ['time_slot_type_id'=>1, 'week_day_number' => 3, 'week_day_en_name' => "Wednesday", 'week_day_ar_name' => "الأربعاء", 'is_off' => 0],
            ['time_slot_type_id'=>2, 'week_day_number' => 0, 'week_day_en_name' => "Sunday", 'week_day_ar_name' => "الأحد", 'is_off' => 0],
            ['time_slot_type_id'=>2, 'week_day_number' => 2, 'week_day_en_name' => "Tuesday", 'week_day_ar_name' => "الثلاثاء", 'is_off' => 0],
            ['time_slot_type_id'=>2, 'week_day_number' => 4, 'week_day_en_name' => "Thursday", 'week_day_ar_name' => "الخميس", 'is_off' => 0],
            ['time_slot_type_id'=>3, 'week_day_number' => 0, 'week_day_en_name' => "Sunday", 'week_day_ar_name' => "الأحد", 'is_off' => 0],
            ['time_slot_type_id'=>3, 'week_day_number' => 1, 'week_day_en_name' => "Monday", 'week_day_ar_name' => "الاثنين", 'is_off' => 0],
            ['time_slot_type_id'=>3, 'week_day_number' => 2, 'week_day_en_name' => "Tuesday", 'week_day_ar_name' => "الثلاثاء", 'is_off' => 0],
            ['time_slot_type_id'=>3, 'week_day_number' => 3, 'week_day_en_name' => "Wednesday", 'week_day_ar_name' => "الأربعاء", 'is_off' => 0],
            ['time_slot_type_id'=>3, 'week_day_number' => 4, 'week_day_en_name' => "Thursday", 'week_day_ar_name' => "الخميس", 'is_off' => 0],
            ['time_slot_type_id'=>3, 'week_day_number' => 5, 'week_day_en_name' => "Friday", 'week_day_ar_name' => "الجمعة", 'is_off' => 0],
            ['time_slot_type_id'=>3, 'week_day_number' => 6, 'week_day_en_name' => "Saturday", 'week_day_ar_name' => "السبت", 'is_off' => 0]
        ];
        DB::table('object_week_days')->insert($types);
    }
}
