<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ObjectDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
         /*   ['object_id' => 1,'time_slot_type_id' => 1, 'description' => "Table #1"],
            ['object_id' => 1,'time_slot_type_id' => 1, 'description' => "Table #2"],
            ['object_id' => 1,'time_slot_type_id' => 1, 'description' => "Table #3"],
            ['object_id' => 2,'time_slot_type_id' => 2, 'description' => "Doctor A"],
            ['object_id' => 2,'time_slot_type_id' => 2, 'description' => "Doctor B"]*/
        ];
        DB::table('object_details')->insert($types);
    }
}
